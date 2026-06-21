<!-- Formulaire de saisie d'un voyage (cote chauffeur) -->
<h1>Proposer un voyage</h1>

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

<!-- Si l'utilisateur n'a aucun vehicule, on l'invite a en ajouter un -->
<?php if (empty($vehicules)): ?>
    <p>Vous devez d abord ajouter un vehicule pour proposer un voyage.</p>
    <p><a href="/vehicule/ajouter">Ajouter un vehicule</a></p>
<?php else: ?>
    <form action="/voyage/creer" method="post">
        <p>
            <label for="ville_depart">Ville de depart</label><br>
            <input type="text" id="ville_depart" name="ville_depart" value="<?= htmlspecialchars($data['ville_depart'] ?? '') ?>" required>
        </p>
        <p>
            <label for="ville_arrivee">Ville d'arrivee</label><br>
            <input type="text" id="ville_arrivee" name="ville_arrivee" value="<?= htmlspecialchars($data['ville_arrivee'] ?? '') ?>" required>
        </p>
        <p>
            <label for="depart">Date et heure de depart</label><br>
            <input type="datetime-local" id="depart" name="depart" value="<?= htmlspecialchars($data['depart'] ?? '') ?>" required>
        </p>
        <p>
            <label for="arrivee">Date et heure d'arrivee</label><br>
            <input type="datetime-local" id="arrivee" name="arrivee" value="<?= htmlspecialchars($data['arrivee'] ?? '') ?>" required>
        </p>
        <p>
            <label for="prix">Prix (credits)</label><br>
            <input type="number" id="prix" name="prix" min="0" value="<?= htmlspecialchars($data['prix'] ?? '') ?>" required>
        </p>
        <p>
            <label for="nb_places">Nombre de places</label><br>
            <input type="number" id="nb_places" name="nb_places" min="1" value="<?= htmlspecialchars($data['nb_places'] ?? '') ?>" required>
        </p>
        <p>
            <label for="id_vehicule">Vehicule</label><br>
            <select id="id_vehicule" name="id_vehicule" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($vehicules as $vehicule): ?>
                    <option value="<?= htmlspecialchars((string) $vehicule['id_vehicule']) ?>" <?= (($data['id_vehicule'] ?? '') == $vehicule['id_vehicule']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($vehicule['marque']) ?> <?= htmlspecialchars($vehicule['modele']) ?> (<?= htmlspecialchars($vehicule['energie']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <button type="submit">Proposer ce voyage</button>
        </p>
    </form>
<?php endif; ?>

<p><a href="/mon-espace">Retour a mon espace</a></p>