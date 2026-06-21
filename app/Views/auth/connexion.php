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
                <label for="mot_de_passe">Mot de passe</label><br>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </p>
            <p>
                <button type="submit">Se connecter</button>
            </p>
        </form>
        <p>Pas encore de compte ? <a href="/inscription">S'inscrire</a></p>
    </main>
</body>
</html>