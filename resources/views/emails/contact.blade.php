Nouveau message depuis le formulaire de contact

Profil : {{ $data['profil'] === 'organisation' ? 'Organisation' : 'Particulier' }}
@if ($data['profil'] === 'organisation')
Organisation : {{ $data['organisation'] }} ({{ $data['type_organisation'] }})
Contact : {{ $data['nom'] }}, {{ $data['fonction'] }}
Soutien envisagé : {{ implode(', ', $data['soutien'] ?? []) ?: '—' }}
Dossier de présentation demandé : {{ ! empty($data['dossier']) ? 'oui' : 'non' }}
@else
Nom : {{ $data['nom'] }}
Objet : {{ $data['objet'] }}
@endif
E-mail : {{ $data['email'] ?? '—' }}
Téléphone : {{ ! empty($data['telephone']) ? $data['telephone'] : '—' }}

{{ $data['message'] }}
