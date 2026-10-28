<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show(): View
    {
        return $this->vue(old('profil', 'particulier'));
    }

    public function institutionnel(): View
    {
        return $this->vue('organisation');
    }

    public function send(ContactRequest $request): RedirectResponse
    {
        // Aucune donnée personnelle n'est écrite dans les journaux.
        $data = $request->safe()->only([
            'profil', 'organisation', 'type_organisation', 'fonction', 'nom', 'email', 'telephone',
            'objet', 'message', 'soutien', 'dossier',
        ]);

        Mail::to(setting('contact.email'))->send(new ContactMessage($data));

        return redirect()->route($request->input('profil') === 'organisation' ? 'contact.institutionnel' : 'contact')
            ->with('contact_envoye', true);
    }

    private function vue(string $profil): View
    {
        return view('site.contact', [
            'profil' => $profil === 'organisation' ? 'organisation' : 'particulier',
            'objets' => ContactRequest::OBJETS,
            'typesOrganisation' => ContactRequest::TYPES_ORGANISATION,
        ]);
    }
}
