<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titre ?? 'EcoRide') ?></title>
</head>
<body>
    <main>
        <h1>Inscription</h1>

        <?php if (!empty($erreurs)): ?>
            <div role="alert">
                <ul>
                    <?php foreach ($erreurs as $erreur): ?>
                        <li><?= htmlspecialchars($erreur) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/inscription" method="post">
            <p>
                <label for="pseudo">Pseudo</label><br>
                <input type="text" id="pseudo" name="pseudo" value="<?= htmlspecialchars($pseudo ?? '') ?>" required>
            </p>
            <p>
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
            </p>
            <p>
                <label for="mot_de_passe">Mot de passe</label><br>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </p>
            <p>
                <button type="submit">Creer mon compte</button>
            </p>
        </form>

        <p>Deja inscrit ? <a href="/connexion">Se connecter</a></p>
    </main>
</body>
</html>