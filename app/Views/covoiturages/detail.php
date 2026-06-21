<!-- Page detail d'un covoiturage -->
<p><a href="/covoiturages">&larr; Retour aux covoiturages</a></p>

<!-- Message flash (succes ou erreur de participation) -->
<?php if (!empty($message)): ?>
    <div role="alert">
        <p><?= htmlspecialchars($message) ?></p>
    </div>
<?php endif; ?>

<h1><?= htmlspecialchars($covoiturage['ville_depart']) ?> &rarr; <?= htmlspecialchars($covoiturage['ville_arrivee']) ?></h1>

<!-- Badge ecologique si vehicule electrique -->
<?php if ($covoiturage['vehicule_energie'] === 'electrique'): ?>
    <p>&#127807; Ce trajet est ecologique (vehicule electrique)</p>
<?php endif; ?>

<h2>Trajet</h2>
<ul>
    <li>Depart : <?= htmlspecialchars($covoiturage['depart']) ?></li>
    <li>Arrivee : <?= htmlspecialchars($covoiturage['arrivee']) ?></li>
    <li>Prix : <?= htmlspecialchars((string) $covoiturage['prix']) ?> credits</li>
    <li>Places disponibles : <?= htmlspecialchars((string) $covoiturage['nb_places']) ?></li>
</ul>

<h2>Chauffeur</h2>
<ul>
    <li>Pseudo : <?= htmlspecialchars($covoiturage['chauffeur']) ?></li>
</ul>

<h2>Vehicule</h2>
<ul>
    <li>Marque et modele : <?= htmlspecialchars($covoiturage['vehicule_marque']) ?> <?= htmlspecialchars($covoiturage['vehicule_modele']) ?></li>
    <li>Couleur : <?= htmlspecialchars($covoiturage['vehicule_couleur']) ?></li>
    <li>Energie : <?= htmlspecialchars($covoiturage['vehicule_energie']) ?></li>
    <li>Nombre de places du vehicule : <?= htmlspecialchars((string) $covoiturage['vehicule_nb_places']) ?></li>
</ul>

<!-- Participation -->
<?php if (isset($_SESSION['user'])): ?>
    <?php if ($covoiturage['nb_places'] > 0): ?>
        <!-- Formulaire de participation (POST) : un bouton qui envoie la demande -->
        <form action="/covoiturage/<?= htmlspecialchars((string) $covoiturage['id_covoiturage']) ?>/participer" method="post">
            <button type="submit">Participer (<?= htmlspecialchars((string) $covoiturage['prix']) ?> credits)</button>
        </form>
    <?php else: ?>
        <p>Ce trajet est complet.</p>
    <?php endif; ?>
<?php else: ?>
    <!-- Visiteur non connecte -->
    <p><a href="/connexion">Connectez-vous pour participer</a></p>
<?php endif; ?>
