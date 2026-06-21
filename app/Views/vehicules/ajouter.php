<!-- Formulaire d'ajout de vehicule -->
<h1>Ajouter un vehicule</h1>

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

<form action="/vehicule/ajouter" method="post">
    <p>
        <label for="immatriculation">Immatriculation</label><br>
        <input type="text" id="immatriculation" name="immatriculation" value="<?= htmlspecialchars($data['immatriculation'] ?? '') ?>" required>
    </p>
    <p>
        <label for="date_premiere_immat">Date de premiere immatriculation</label><br>
        <input type="date" id="date_premiere_immat" name="date_premiere_immat" value="<?= htmlspecialchars($data['date_premiere_immat'] ?? '') ?>">
    </p>
    <p>
        <label for="modele">Modele</label><br>
        <input type="text" id="modele" name="modele" value="<?= htmlspecialchars($data['modele'] ?? '') ?>" required>
    </p>
    <p>
        <label for="couleur">Couleur</label><br>
        <input type="text" id="couleur" name="couleur" value="<?= htmlspecialchars($data['couleur'] ?? '') ?>">
    </p>
    <p>
        <label for="nb_places">Nombre de places</label><br>
        <input type="number" id="nb_places" name="nb_places" min="1" value="<?= htmlspecialchars($data['nb_places'] ?? '') ?>" required>
    </p>
    <p>
        <label for="energie">Energie</label><br>
        <!-- Menu deroulant : electrique met en avant l'aspect ecologique -->
        <select id="energie" name="energie" required>
            <option value="">-- Choisir --</option>
            <option value="electrique" <?= (($data['energie'] ?? '') === 'electrique') ? 'selected' : '' ?>>Electrique</option>
            <option value="hybride" <?= (($data['energie'] ?? '') === 'hybride') ? 'selected' : '' ?>>Hybride</option>
            <option value="essence" <?= (($data['energie'] ?? '') === 'essence') ? 'selected' : '' ?>>Essence</option>
            <option value="diesel" <?= (($data['energie'] ?? '') === 'diesel') ? 'selected' : '' ?>>Diesel</option>
        </select>
    </p>
    <p>
        <label for="id_marque">Marque</label><br>
        <!-- Menu deroulant alimente par la table marque -->
        <select id="id_marque" name="id_marque" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($marques as $marque): ?>
                <option value="<?= htmlspecialchars((string) $marque['id_marque']) ?>" <?= (($data['id_marque'] ?? '') == $marque['id_marque']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($marque['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <button type="submit">Ajouter le vehicule</button>
    </p>
</form>

<p><a href="/mon-espace">Retour a mon espace</a></p>