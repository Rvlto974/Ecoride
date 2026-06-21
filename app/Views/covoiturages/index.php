<h1>Covoiturages disponibles</h1>

<!-- Recherche classique par ville et date (rechargement de page) -->
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

<!-- Filtres dynamiques (AJAX : mise a jour sans recharger la page) -->
<fieldset>
    <legend>Filtres</legend>
    <p>
        <input type="checkbox" id="filtre-eco">
        <label for="filtre-eco">Trajets ecologiques uniquement</label>
    </p>
    <p>
        <label for="filtre-prix">Prix maximum (credits)</label><br>
        <input type="number" id="filtre-prix" min="0">
    </p>
    <p>
        <label for="filtre-places">Places minimum</label><br>
        <input type="number" id="filtre-places" min="1">
    </p>
</fieldset>

<!-- Zone des resultats : c'est ce que le JavaScript va mettre a jour -->
<ul id="resultats">
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
            <br>
            <!-- Lien vers la page detaillee de ce trajet -->
            <a href="/covoiturage/<?= htmlspecialchars((string) $trajet['id_covoiturage']) ?>">Voir le detail</a>
        </li>
    <?php endforeach; ?>
</ul>

<!-- On charge le script qui gere les filtres AJAX -->
<script src="/js/covoiturages.js"></script>
