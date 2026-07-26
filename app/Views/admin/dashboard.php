<!-- Tableau de bord administrateur -->
<h1>Administration</h1>
<p>Bienvenue dans l'espace administrateur d'EcoRide.</p>

<!-- Cartes de statistiques -->
<div class="stats-grille">
    <div class="stat-carte">
        <span class="stat-valeur"><?= htmlspecialchars((string) $nbUtilisateurs) ?></span>
        <span class="stat-label">Utilisateurs inscrits</span>
    </div>
    <div class="stat-carte">
        <span class="stat-valeur"><?= htmlspecialchars((string) $nbCovoiturages) ?></span>
        <span class="stat-label">Covoiturages proposes</span>
    </div>
    <div class="stat-carte">
        <span class="stat-valeur"><?= htmlspecialchars((string) $nbParticipations) ?></span>
        <span class="stat-label">Participations</span>
    </div>
    <div class="stat-carte">
        <span class="stat-valeur"><?= htmlspecialchars((string) $totalCredits) ?></span>
        <span class="stat-label">Credits en circulation</span>
    </div>
</div>

<!-- Covoiturages par jour -->
<h2>Covoiturages par jour</h2>

<?php if (empty($parJour)): ?>
    <p>Aucune donnee disponible.</p>
<?php else: ?>

    <!-- Graphique en barres (CSS pur) -->
    <?php $maxTotal = max(array_column($parJour, 'total')); ?>
    <div class="graphique" role="img" aria-label="Graphique du nombre de covoiturages par jour">
        <?php foreach ($parJour as $ligne): ?>
            <div class="graph-colonne">
                <div class="graph-barre" style="--valeur: <?= (int) $ligne['total'] ?>; --max: <?= (int) $maxTotal ?>;">
                    <span class="graph-nombre"><?= htmlspecialchars((string) $ligne['total']) ?></span>
                </div>
                <span class="graph-jour"><?= htmlspecialchars($ligne['jour']) ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Tableau detaille -->
    <table class="stats-table">
        <thead>
            <tr>
                <th>Jour</th>
                <th>Nombre de covoiturages</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($parJour as $ligne): ?>
                <tr>
                    <td><?= htmlspecialchars($ligne['jour']) ?></td>
                    <td><?= htmlspecialchars((string) $ligne['total']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>