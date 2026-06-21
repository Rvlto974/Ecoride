<!-- Formulaire de creation d'un compte employe -->
<h1>Creer un compte employe</h1>

<!-- Affichage des erreurs -->
<?php if (!empty($erreurs)): ?>
    <div role="alert">
        <ul>
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= htmlspecialchars($erreur) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="/admin/employe/creer" method="post">
    <p>
        <label for="pseudo">Pseudo</label><br>
        <input type="text" id="pseudo" name="pseudo" value="<?= htmlspecialchars($pseudo ?? '') ?>" required>
    </p>
    <p>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
    </p>
    <p>
        <label for="mot_de_passe">Mot de passe (8 caracteres minimum)</label><br>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>
    </p>
    <p>
        <button type="submit">Creer l'employe</button>
    </p>
</form>

<p><a href="/admin/comptes">Retour a la gestion des comptes</a></p>
