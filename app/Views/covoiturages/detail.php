<!-- Page detail d'un covoiturage -->
<main class="detail-page">

    <a href="/covoiturages" class="lien-retour">&larr; Retour aux covoiturages</a>

    <!-- Message flash (succes ou erreur de participation) -->
    <?php if (!empty($message)): ?>
        <div role="alert"><p><?= htmlspecialchars($message) ?></p></div>
    <?php endif; ?>

    <h1 class="trajet-titre"><?= htmlspecialchars($covoiturage['ville_depart']) ?> &rarr; <?= htmlspecialchars($covoiturage['ville_arrivee']) ?></h1>
    <p class="trajet-date"><?= htmlspecialchars($covoiturage['depart']) ?></p>

    <div class="detail-grille">

        <!-- Colonne principale -->
        <article class="trajet-carte">

            <h2 class="carte-titre">Details du trajet</h2>
            <ol class="timeline">
                <li class="etape">
                    <span class="etape-point etape-depart"></span>
                    <div class="etape-infos">
                        <span class="etape-ville">Depart &mdash; <?= htmlspecialchars($covoiturage['ville_depart']) ?></span>
                        <span class="etape-heure"><?= htmlspecialchars($covoiturage['depart']) ?></span>
                    </div>
                </li>
                <li class="etape">
                    <span class="etape-point etape-arrivee"></span>
                    <div class="etape-infos">
                        <span class="etape-ville">Arrivee &mdash; <?= htmlspecialchars($covoiturage['ville_arrivee']) ?></span>
                        <span class="etape-heure"><?= htmlspecialchars($covoiturage['arrivee']) ?></span>
                    </div>
                </li>
            </ol>

            <hr class="separateur">

            <h2 class="carte-titre">Conducteur</h2>
            <div class="conducteur">
                <span class="avatar"><?= htmlspecialchars(strtoupper(substr($covoiturage['chauffeur'], 0, 1))) ?></span>
                <span class="conducteur-nom"><?= htmlspecialchars($covoiturage['chauffeur']) ?></span>
                <?php if ($moyenne !== null): ?>
                    <span class="conducteur-note">&#9733; <?= htmlspecialchars((string) $moyenne) ?>/5</span>
                <?php else: ?>
                    <span class="conducteur-note">Pas encore d'avis</span>
                <?php endif; ?>
            </div>

            <!-- Vehicule -->
            <div class="vehicule">
                <div>
                    <span class="vehicule-modele"><?= htmlspecialchars($covoiturage['vehicule_marque']) ?> <?= htmlspecialchars($covoiturage['vehicule_modele']) ?></span>
                    <span class="vehicule-places"><?= htmlspecialchars($covoiturage['vehicule_couleur']) ?> &middot; <?= htmlspecialchars((string) $covoiturage['vehicule_nb_places']) ?> places</span>
                </div>
                <?php if ($covoiturage['vehicule_energie'] === 'electrique'): ?>
                    <span class="badge-eco">&#127807; Electrique</span>
                <?php endif; ?>
            </div>

            <!-- Avis -->
            <h2 class="carte-titre">Avis sur le chauffeur</h2>
            <?php if (empty($avis)): ?>
                <p class="avis-vide">Aucun avis pour le moment.</p>
            <?php else: ?>
                <ul class="avis-liste">
                    <?php foreach ($avis as $unAvis): ?>
                        <li class="avis-item">
                            <span class="avis-note">&#9733; <?= htmlspecialchars((string) $unAvis['note']) ?>/5</span>
                            <span class="avis-auteur">par <?= htmlspecialchars($unAvis['pseudo_passager']) ?></span>
                            <p class="avis-commentaire"><?= htmlspecialchars($unAvis['commentaire']) ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

        </article>

        <!-- Colonne prix -->
        <aside class="prix-carte">
            <p class="prix-label">Prix par passager</p>
            <p class="prix-montant"><strong><?= htmlspecialchars((string) $covoiturage['prix']) ?></strong> credits</p>
            <hr class="separateur">
            <div class="places-dispo">
                <span>Places disponibles</span>
                <span><?= htmlspecialchars((string) $covoiturage['nb_places']) ?></span>
            </div>

            <?php if (isset($_SESSION['user'])): ?>
                <?php if ($covoiturage['nb_places'] > 0): ?>
                    <form action="/covoiturage/<?= htmlspecialchars((string) $covoiturage['id_covoiturage']) ?>/participer" method="post">
                        <button type="submit" class="btn-participer">Participer</button>
                    </form>
                <?php else: ?>
                    <p class="complet">Ce trajet est complet.</p>
                <?php endif; ?>
            <?php else: ?>
                <a href="/connexion" class="btn-participer btn-connexion">Connectez-vous pour participer</a>
            <?php endif; ?>
        </aside>

    </div>
</main>