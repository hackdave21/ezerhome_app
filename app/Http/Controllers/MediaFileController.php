<?php

namespace App\Http\Controllers;

use App\Models\MediaAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Seul point d'accès aux fichiers : URL stable par identifiant. Un fichier privé n'est jamais
 * servi par son chemin disque ; il passe par ce contrôleur et par la policy.
 */
class MediaFileController extends Controller
{
    public function show(Request $request, MediaAsset $asset, ?string $variant = null): StreamedResponse
    {
        abort_unless($asset->isReady(), 404);

        // Visiteurs : fichiers publiables seulement. Connecté : policy (Admin, ou Comité pour « assistance »).
        abort_unless(Gate::forUser($request->user())->allows('view', $asset), $request->user() ? 403 : 404);

        $path = $variant === 'miniature' && $asset->thumb_path ? $asset->thumb_path : $asset->path;
        $disk = Storage::disk($asset->disk);

        abort_unless($disk->exists($path), 404);

        $headers = [
            'Content-Type' => $variant === 'miniature' ? 'image/webp' : $asset->mime,
            'X-Content-Type-Options' => 'nosniff',
            // Un SVG servi seul ne doit jamais pouvoir exécuter de script, même s'il en restait un.
            'Content-Security-Policy' => "default-src 'none'; style-src 'unsafe-inline'; sandbox",
            'Cache-Control' => $asset->isPubliclyServed() ? 'public, max-age=3600' : 'private, no-store',
            'ETag' => '"'.md5($path.$asset->updated_at).'"',
        ];

        return $disk->response($path, null, $headers);
    }
}
