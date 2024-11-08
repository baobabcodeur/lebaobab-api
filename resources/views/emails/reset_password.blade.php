<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
</head>
<body>
    <h1>Bonjour {{ $name }}</h1>
    <p>Vous avez demandé à réinitialiser votre mot de passe.</p>
    <p>Pour réinitialiser votre mot de passe, cliquez sur le lien ci-dessous :</p>
    <a href="{{ url('api/password/reset?token=' . $token) }}">Réinitialiser le mot de passe</a>
    <p>Si vous n'avez pas demandé cette action, ignorez ce message.</p>
</body>
</html>
