<!-- Formulaire pour laisser un avis sur un trajet -->
<h1>Laisser un avis</h1>

<p>
    Trajet : <strong><?= htmlspecialchars($covoiturage['ville_depart']) ?> &rarr; <?= htmlspecialchars($covoiturage['ville_arrivee']) ?></strong><br>
    Chauffeur : <?= htmlspecialchars($covoiturage['chauffeur']) ?>
</p>

<!-- Affichage des erreurs de validation -->
<?php if (!empty($erreurs)): ?>
    <div role="alert">
        <ul>
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= htmlspecialchars($erreur) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="/avis/<?= htmlspecialchars((string) $covoiturage['id_covoiturage']) ?>/enregistrer" method="post">
    <p>
        <label for="note">Note (de 1 a 5)</label><br>
        <select id="note" name="note" required>
            <option value="">-- Choisir --</option>
            <option value="1">1 - Tres decevant</option>
            <option value="2">2 - Decevant</option>
            <option value="3">3 - Correct</option>
            <option value="4">4 - Bien</option>
            <option value="5">5 - Excellent</option>
        </select>
    </p>
    <p>
        <label for="commentaire">Commentaire</label><br>
        <textarea id="commentaire" name="commentaire" rows="4" required></textarea>
    </p>
    <p>
        <button type="submit">Envoyer mon avis</button>
    </p>
</form>

<p><a href="/mon-espace">Retour a mon espace</a></p>
