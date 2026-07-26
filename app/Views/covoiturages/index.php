<h1 class="liste-titre">Covoiturages disponibles</h1>

<!-- Recherche classique par ville et date (rechargement de page) -->
<form action="/covoiturages" method="get" role="search" class="recherche-barre">
    <div class="champ">
        <label for="depart">Ville de depart</label>
        <input type="text" id="depart" name="depart" value="<?= htmlspecialchars($depart ?? '') ?>">
    </div>
    <div class="champ">
        <label for="arrivee">Ville d'arrivee</label>
        <input type="text" id="arrivee" name="arrivee" value="<?= htmlspecialchars($arrivee ?? '') ?>">
    </div>
    <div class="champ">
        <label for="date">Date</label>
        <input type="date" id="date" name="date" value="<?= htmlspecialchars($date ?? '') ?>">
    </div>
    <button type="submit">Rechercher</button>
</form>

<div class="liste-layout">

    <!-- Filtres dynamiques (AJAX) -->
    <fieldset class="filtres">
        <legend>Filtres</legend>
        <p class="filtre-check">
            <input type="checkbox" id="filtre-eco">
            <label for="filtre-eco">Trajets ecologiques uniquement</label>
        </p>
        <div class="champ">
            <label for="filtre-prix">Prix maximum (credits)</label>
            <input type="number" id="filtre-prix" min="0">
        </div>
        <div class="champ">
            <label for="filtre-places">Places minimum</label>
            <input type="number" id="filtre-places" min="1">
        </div>
    </fieldset>

    <!-- Zone des resultats : mise a jour par le JavaScript -->
    <ul id="resultats" class="trajets">
        <?php foreach ($covoiturages as $trajet): ?>
            <li class="trajet-carte-liste">
                <div class="trajet-infos">
                    <p class="trajet-route">
                        <span class="point-depart"></span>
                        <strong><?= htmlspecialchars($trajet['ville_depart']) ?></strong>
                        <span class="fleche">&rarr;</span>
                        <strong><?= htmlspecialchars($trajet['ville_arrivee']) ?></strong>
                        <?php if ($trajet['vehicule_energie'] === 'electrique'): ?>
                            <span class="badge-eco">&#127807; Electrique</span>
                        <?php endif; ?>
                    </p>
                    <p class="trajet-chauffeur">
                        <span class="avatar-mini"><?= htmlspecialchars(strtoupper(substr($trajet['chauffeur'], 0, 1))) ?></span>
                        <?= htmlspecialchars($trajet['chauffeur']) ?> &middot;
                        <?= htmlspecialchars($trajet['vehicule_marque']) ?> <?= htmlspecialchars($trajet['vehicule_modele']) ?> &middot;
                        <?= htmlspecialchars((string) $trajet['nb_places']) ?> places
                    </p>
                    <p class="trajet-heure"><?= htmlspecialchars($trajet['depart']) ?></p>
                </div>
                <div class="trajet-action">
                    <span class="trajet-prix"><?= htmlspecialchars((string) $trajet['prix']) ?> <small>credits</small></span>
                    <a href="/covoiturage/<?= htmlspecialchars((string) $trajet['id_covoiturage']) ?>" class="btn-voir">Voir</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

</div>

<script src="/js/covoiturages.js"></script>