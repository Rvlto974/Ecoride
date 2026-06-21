<!-- Contenu de la page de connexion -->
<h1>Connexion</h1>

<!-- Affichage des erreurs s'il y en a (ex : mauvais mot de passe) -->
<?php if (!empty($erreurs)): ?>
    <div role="alert">
        <ul>
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= htmlspecialchars($erreur) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- method="post" : on n'envoie pas le mot de passe dans l'URL -->
<form action="/connexion" method="post">
    <p>
        <label for="email">Email</label><br>
        <!-- value pre-rempli pour ne pas retaper l'email en cas d'erreur -->
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
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