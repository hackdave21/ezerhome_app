<?php

namespace App\Http\Controllers;

use App\Jobs\ExportUserData;
use App\Models\DataExport;
use App\Services\Otp\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PrivacyController extends Controller
{
    /** Droit d'accès : l'export est préparé par un job en file d'attente. */
    public function requestExport(Request $request): RedirectResponse
    {
        $user = $request->user();

        $export = DataExport::create(['user_id' => $user->id]);
        ExportUserData::dispatch($export);

        return back()->with('status', 'Votre demande est enregistrée. Vous serez prévenu(e) dès que le fichier est prêt.');
    }

    public function download(Request $request, DataExport $export): StreamedResponse
    {
        abort_unless($export->user_id === $request->user()->id && $export->isDownloadable(), 404);

        return Storage::disk('local')->download($export->path, 'mes-donnees-ezer-home.json');
    }

    /** Suppression : confirmée par un code envoyé au compte. */
    public function requestDeletion(Request $request, OtpService $otp): RedirectResponse
    {
        $user = $request->user();
        $identifier = ($user->phone && $otp->smsEnabled()) ? $user->phone : $user->email;

        if (! $identifier) {
            return back()->with('status', 'Ajoutez une adresse e-mail à votre compte pour confirmer la suppression.');
        }

        if (! $otp->sentRecently($identifier, OtpService::DELETE)) {
            $otp->issue($identifier, OtpService::DELETE, $user);
        }

        $request->session()->put('otp', [
            'identifier' => $identifier, 'purpose' => OtpService::DELETE, 'back' => route('espace.profil'),
        ]);

        return redirect()->route('otp.show');
    }
}
