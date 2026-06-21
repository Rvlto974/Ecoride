<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titre ?? 'EcoRide') ?></title>
</head>
<body>
    <main>
        <h1>Connexion</h1>

        <form action="/connexion" method="post">
            <p>
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email" required>
            </p>
            <p>
