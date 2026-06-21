<h1>Covoiturages disponibles</h1>

<form action="/covoiturages" method="get" role="search">
    <p>
        <label for="depart">Ville de depart</label><br>
        <input type="text" id="depart" name="depart" value="<?= htmlspecialchars($depart ?? '') ?>">
    </p>
    <p>
        <label for="arrivee">Ville d'arrivee</label><br>
        <input type="text" id="arrivee" name="arrivee" value="<?= htmlspecialchars($arrivee ?? '') ?>">
    </p>
    <p>
        <label for="date">Date</label><br>
        <input type="date" id="date" name="date" value="<?= htmlspecialchars($date ?? '') ?>">
    </p>
    <p><button type="submit">Rechercher</button></p>
</form>

<hr>

<?php if (empty($covoiturages)): ?>
    <p>Aucun covoiturage disponible pour ces criteres.</p>
<?php else: ?>
    <ul>
        <?php foreach ($covoiturages as $trajet): ?>
            <li>
                <strong><?= htmlspecialchars($trajet['ville_depart']) ?> &rarr; <?= htmlspecialchars($trajet['ville_arrivee']) ?></strong><br>
                Depart : <?= htmlspecialchars($trajet['depart']) ?><br>
                Prix : <?= htmlspecialchars((string) $trajet['prix']) ?> credits<br>
                Places : <?= htmlspecialchars((string) $trajet['nb_places']) ?><br>
                Chauffeur : <?= htmlspecialchars($trajet['chauffeur']) ?><br>
                Vehicule : <?= htmlspecialchars($trajet['vehicule_marque']) ?> <?= htmlspecialchars($trajet['vehicule_modele']) ?>
                <?php if ($trajet['vehicule_energie'] === 'electrique'): ?>
                    &#127807; Ecologique
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>