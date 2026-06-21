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

<!-- Tableau : covoiturages par jour -->
<h2>Covoiturages par jour</h2>
<?php if (empty($parJour)): ?>
    <p>Aucune donnee disponible.</p>
<?php else: ?>
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
