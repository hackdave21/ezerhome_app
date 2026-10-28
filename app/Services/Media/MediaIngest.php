<?php

namespace App\Services\Media;

use App\Jobs\ProcessMediaAsset;
use App\Models\MediaAsset;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/** Réception d'un fichier : contrôle du type réel, mise à l'abri sur le disque privé, traitement en file d'attente. */
class MediaIngest
{
    /** Types réels acceptés (détectés sur le contenu, jamais d'après l'extension). */
    public const MIMES = [
        'image/jpeg' => 'JPG', 'image/png' => 'PNG', 'image/webp' => 'WebP', 'image/svg+xml' => 'SVG', 'application/pdf' => 'PDF',
    ];

    public static function maxKilobytes(): int
    {
        return max(1, (int) setting('media.max_mb', '5')) * 1024;
    }

    /** Type réel du fichier, ou null s'il n'est pas reconnu. */
    public function detectMime(string $path): ?string
    {
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($path);

        // finfo reconnaît mal les SVG (text/plain, text/xml, image/svg) : on vérifie la racine du document.
        if (in_array($mime, ['image/svg+xml', 'image/svg', 'text/xml', 'text/plain', 'application/xml'], true)) {
            $head = (string) file_get_contents($path, false, null, 0, 4096);

            return preg_match('/<svg[\s>]/i', $head) ? 'image/svg+xml' : null;
        }

        return $mime;
    }

    /**
     * Message d'erreur en français si le fichier est refusé, sinon null.
     *
     * @param  list<string>  $allowed  types réels autorisés
     */
    public function rejection(UploadedFile $file, array $allowed): ?string
    {
        if ($file->getSize() > self::maxKilobytes() * 1024) {
            return 'Le fichier « '.$file->getClientOriginalName().' » dépasse '.(self::maxKilobytes() / 1024).' Mo.';
        }

        $mime = $this->detectMime($file->getRealPath());

        if ($mime === null || ! in_array($mime, $allowed, true)) {
            $noms = collect($allowed)->map(fn ($m) => self::MIMES[$m] ?? $m)->implode(', ');

            return 'Le fichier « '.$file->getClientOriginalName()."» n'est pas d'un type accepté ({$noms}).";
        }

        return null;
    }

    /** Enregistre un nouveau fichier et lance son traitement. */
    public function store(UploadedFile $file, User $user, array $attributes = []): MediaAsset
    {
        $name = $file->getClientOriginalName();

        $asset = MediaAsset::create(array_merge([
            'original_name' => $name,
            'title' => Str::of(pathinfo($name, PATHINFO_FILENAME))->replace(['-', '_'], ' ')->limit(120, '')->toString(),
            'category' => MediaAsset::GENERAL,
            'uploaded_by' => $user->id,
            'status' => 'processing',
        ], $attributes));

        $this->queue($asset, $file);

        return $asset;
    }

    /** Remplace le fichier d'un média en conservant son identifiant (et donc ses liens). */
    public function replace(MediaAsset $asset, UploadedFile $file): MediaAsset
    {
        $asset->update(['original_name' => $file->getClientOriginalName()]);
        $this->queue($asset, $file);

        return $asset;
    }

    private function queue(MediaAsset $asset, UploadedFile $file): void
    {
        $incoming = 'incoming/'.Str::uuid();
        Storage::disk(MediaAsset::PRIVATE_DISK)->put($incoming, file_get_contents($file->getRealPath()));

        $asset->update(['incoming_path' => $incoming]);

        ProcessMediaAsset::dispatch($asset);
    }
}
