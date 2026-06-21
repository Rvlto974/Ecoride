<!-- Page liste des covoiturages -->
<h1>Covoiturages disponibles</h1>

<?php if (empty($covoiturages)): ?>
    <!-- Cas ou il n'y a aucun trajet -->
    <p>Aucun covoiturage disponible pour le moment.</p>
<?php else: ?>
    <!-- On boucle sur chaque trajet recupere en base -->
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