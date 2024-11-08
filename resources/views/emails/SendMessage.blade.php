<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre OTP</title>
</head>
<body>
    <h1>Bonjour {{ $fullName }},</h1>
    <p>{{ $message }}</p>
    <p>Votre OTP est : <strong>{{ $otp }}</strong></p>
    <p>Voici les informations que vous avez envoyées :</p>
    <ul>
        <li>Email : {{ $email }}</li>
        <li>Numéro de téléphone : {{ $phoneNumber }}</li>
    </ul>
</body>
</html>
