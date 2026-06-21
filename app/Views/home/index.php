<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titre ?? 'EcoRide') ?></title>
</head>
<body>
    <header>
        <nav aria-label="Navigation principale">
            <a href="/">EcoRide</a>

            <?php if ($utilisateur !== null): ?>
                <span>Bonjour <?= htmlspecialchars($utilisateur['pseudo']) ?> (<?= htmlspecialchars((string) $utilisateur['credits']) ?> credits)</span>
                <a href="/deconnexion">Deconnexion</a>
            <?php else: ?>
                <a href="/connexion">Connexion</a>
                <a href="/inscription">Inscription</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <h1>EcoRide</h1>
        <p>Bienvenue sur EcoRide, la plateforme de covoiturage ecologique.</p>
    </main>
</body>
</html>