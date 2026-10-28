<?php

namespace App\Jobs;

use App\Models\MediaAsset;
use App\Services\Media\MediaIngest;
use App\Services\Media\SvgSanitizer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use RuntimeException;
use Throwable;

/**
 * Traitement automatique d'un fichier reçu :
 * images → largeur max 1600 px, conversion WebP, miniature, métadonnées EXIF supprimées (ré-encodage) ;
 * SVG → assainissement ; PDF → conservé tel quel.
 */
class ProcessMediaAsset implements ShouldQueue
{
    use Queueable;

    public const MAX_WIDTH = 1600;

    public const THUMB_WIDTH = 400;

    public function __construct(public MediaAsset $asset) {}

    public function handle(MediaIngest $ingest, SvgSanitizer $svg): void
    {
        $asset = $this->asset->fresh();

        if (! $asset || ! $asset->incoming_path) {
            return;
        }

        $private = Storage::disk(MediaAsset::PRIVATE_DISK);
        $incoming = $asset->incoming_path;
        $source = $private->path($asset->incoming_path);
        $mime = $ingest->detectMime($source);
        $targetDisk = $asset->diskFor($asset->is_public);
        $base = date('Y/m').'/'.Str::uuid();

        $old = ['disk' => $asset->disk, 'path' => $asset->path, 'thumb' => $asset->thumb_path];

        $result = match (true) {
            in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true) => $this->image($source, $targetDisk, $base),
            $mime === 'image/svg+xml' => $this->svg($source, $targetDisk, $base, $svg),
            $mime === 'application/pdf' => $this->pdf($source, $targetDisk, $base),
            default => throw new RuntimeException('Type de fichier non pris en charge.'),
        };

        $asset->update($result + ['disk' => $targetDisk, 'status' => 'ready', 'incoming_path' => null]);

        $private->delete($incoming);

        // Remplacement : l'ancien fichier n'est supprimé qu'une fois le nouveau en place.
        foreach ([$old['path'], $old['thumb']] as $path) {
            if ($path && $path !== $asset->path && $path !== $asset->thumb_path) {
                Storage::disk($old['disk'])->delete($path);
            }
        }
    }

    public function failed(Throwable $e): void
    {
        $asset = $this->asset->fresh();

        Log::warning('Traitement du média #'.$this->asset->id.' impossible : '.$e->getMessage());

        if (! $asset) {
            return;
        }

        if ($asset->incoming_path) {
            Storage::disk(MediaAsset::PRIVATE_DISK)->delete($asset->incoming_path);
        }

        // Un remplacement raté laisse l'ancien fichier en place.
        $asset->update(['incoming_path' => null, 'status' => $asset->path ? 'ready' : 'failed']);
    }

    /** @return array<string, mixed> */
    private function image(string $source, string $disk, string $base): array
    {
        $manager = new ImageManager(new Driver);

        // Le ré-encodage ne recopie aucune métadonnée : la géolocalisation EXIF disparaît.
        $image = $manager->read($source)->scaleDown(width: self::MAX_WIDTH);
        $webp = (string) $image->toWebp(82);

        $thumb = (string) $manager->read($source)->scaleDown(width: self::THUMB_WIDTH)->toWebp(78);

        Storage::disk($disk)->put($base.'.webp', $webp);
        Storage::disk($disk)->put($base.'-miniature.webp', $thumb);

        return [
            'path' => $base.'.webp', 'thumb_path' => $base.'-miniature.webp', 'mime' => 'image/webp',
            'size' => strlen($webp), 'width' => $image->width(), 'height' => $image->height(),
        ];
    }

    /** @return array<string, mixed> */
    private function svg(string $source, string $disk, string $base, SvgSanitizer $sanitizer): array
    {
        $clean = $sanitizer->sanitize((string) file_get_contents($source));

        if ($clean === null) {
            throw new RuntimeException('SVG invalide.');
        }

        Storage::disk($disk)->put($base.'.svg', $clean);

        return ['path' => $base.'.svg', 'thumb_path' => null, 'mime' => 'image/svg+xml', 'size' => strlen($clean), 'width' => null, 'height' => null];
    }

    /** @return array<string, mixed> */
    private function pdf(string $source, string $disk, string $base): array
    {
        $contents = (string) file_get_contents($source);
        Storage::disk($disk)->put($base.'.pdf', $contents);

        return ['path' => $base.'.pdf', 'thumb_path' => null, 'mime' => 'application/pdf', 'size' => strlen($contents), 'width' => null, 'height' => null];
    }
}
