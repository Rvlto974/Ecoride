<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Titre dynamique : chaque page envoie son propre $titre -->
    <title><?= htmlspecialchars($titre ?? 'EcoRide') ?></title>
</head>
<body>
    <!-- ===== EN-TETE + MENU (commun a toutes les pages) ===== -->
    <header>
        <nav aria-label="Navigation principale">
            <a href="/">EcoRide</a>
            <a href="/covoiturages">Covoiturages</a>

            <!-- Menu dynamique : on lit la session pour savoir si connecte -->
            <?php if (isset($_SESSION['user'])): ?>
                <!-- Connecte : pseudo + credits + deconnexion -->
                <span>Bonjour <?= htmlspecialchars($_SESSION['user']['pseudo']) ?> (<?= htmlspecialchars((string) $_SESSION['user']['credits']) ?> credits)</span>
                <a href="/deconnexion">Deconnexion</a>
            <?php else: ?>
                <!-- Visiteur : connexion / inscription -->
                <a href="/connexion">Connexion</a>
                <a href="/inscription">Inscription</a>
            <?php endif; ?>
        </nav>
    </header>

    <!-- ===== CONTENU DE LA PAGE (injecte par le Controller) ===== -->
    <main id="contenu">
        <?= $contenu ?>
    </main>

    <!-- ===== PIED DE PAGE (commun a toutes les pages) ===== -->
    <footer>
        <p>
            <a href="mailto:contact@ecoride.fr">contact@ecoride.fr</a>
            - <a href="/mentions-legales">Mentions legales</a>
        </p>
        <p>&copy; <?= date('Y') ?> EcoRide</p>
    </footer>
</body>
</html>