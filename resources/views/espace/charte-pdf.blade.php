<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Charte d'écoute — Ezer Home</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #0e1c03; font-size: 12px; line-height: 1.6; }
        h1 { font-size: 22px; margin: 0 0 4px; }
        h2 { font-size: 14px; margin: 18px 0 4px; }
        .meta { color: #5b6656; font-size: 11px; margin-bottom: 18px; }
        .num { color: #187d50; }
        footer { margin-top: 30px; border-top: 1px solid #c8dccf; padding-top: 8px; color: #5b6656; font-size: 10px; }
    </style>
</head>
<body>
    <h1>La charte d'écoute</h1>
    <p class="meta">Ezer Home · Version {{ $charter->version }} · en vigueur depuis le {{ $charter->published_at->locale('fr')->translatedFormat('j F Y') }}</p>

    {!! $charter->body !!}

    <footer>Ezer Home · {{ setting('contact.adresse') }} · {{ setting('contact.telephone') }} · {{ setting('contact.email') }}</footer>
</body>
</html>
