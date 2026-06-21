<!-- Espace utilisateur : profil + historique -->
<h1>Mon espace</h1>

<!-- Message flash (ex : avis envoye) -->
<?php if (!empty($_SESSION['message'])): ?>
    <div role="alert">
        <p><?= htmlspecialchars($_SESSION['message']) ?></p>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<!-- Rappel des infos du compte (depuis la session) -->
<h2>Mon profil</h2>
<ul>
    <li>Pseudo : <?= htmlspecialchars($_SESSION['user']['pseudo']) ?></li>
    <li>Role : <?= htmlspecialchars($_SESSION['user']['role']) ?></li>
    <li>Credits : <?= htmlspecialchars((string) $_SESSION['user']['credits']) ?></li>
</ul>

<!-- Liens rapides vers les actions chauffeur -->
<h2>Actions</h2>
<p>
    <a href="/vehicule/ajouter">Ajouter un vehicule</a> &nbsp;|&nbsp;
    <a href="/voyage/creer">Proposer un voyage</a>
</p>

<h2>Mes trajets reserves</h2>

<?php if (empty($participations)): ?>
    <p>Vous ne participez a aucun trajet pour le moment.</p>
    <p><a href="/covoiturages">Chercher un covoiturage</a></p>
<?php else: ?>
    <ul>
        <?php foreach ($participations as $trajet): ?>
            <li>
                <strong><?= htmlspecialchars($trajet['ville_depart']) ?> &rarr; <?= htmlspecialchars($trajet['ville_arrivee']) ?></strong><br>
                Depart : <?= htmlspecialchars($trajet['depart']) ?><br>
                Chauffeur : <?= htmlspecialchars($trajet['chauffeur']) ?><br>
                Vehicule : <?= htmlspecialchars($trajet['vehicule_marque']) ?> <?= htmlspecialchars($trajet['vehicule_modele']) ?><br>
                Credits utilises : <?= htmlspecialchars((string) $trajet['credits_utilises']) ?><br>
                Statut : <?= htmlspecialchars($trajet['participation_statut']) ?><br>
                <a href="/covoiturage/<?= htmlspecialchars((string) $trajet['id_covoiturage']) ?>">Voir le detail</a>
                &nbsp;|&nbsp;
                <!-- Bouton pour laisser un avis sur ce trajet -->
                <a href="/avis/<?= htmlspecialchars((string) $trajet['id_covoiturage']) ?>">Laisser un avis</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
