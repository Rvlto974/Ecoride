<?php
// Script de test : verifie que la connexion MongoDB et l'insertion fonctionnent
// A lancer une seule fois pour valider, puis a supprimer

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\AvisModel;

echo "=== Test MongoDB EcoRide ===\n";

try {
    $avisModel = new AvisModel();

    // 1. On insere un avis de test
    echo "Insertion d'un avis de test...\n";
    $succes = $avisModel->creer([
        'id_passager'      => 5,
        'pseudo_passager'  => 'Mathieu',
        'id_chauffeur'     => 3,
        'pseudo_chauffeur' => 'Lucas',
        'note'             => 5,
        'commentaire'      => 'Tres bon trajet, chauffeur ponctuel et sympathique !',
    ]);

    if ($succes) {
        echo "OK : avis insere avec succes dans MongoDB.\n";
    } else {
        echo "ECHEC : l'insertion n'a pas fonctionne.\n";
    }

    // 2. On relit les avis en attente pour verifier
    echo "Lecture des avis en attente...\n";
    $enAttente = $avisModel->findEnAttente();
    echo "Nombre d'avis en attente : " . count($enAttente) . "\n";

    // 3. On affiche le contenu du dernier avis
    if (!empty($enAttente)) {
        $dernier = end($enAttente);
        echo "Dernier avis : note " . $dernier['note'] . "/5 par " . $dernier['pseudo_passager'] . "\n";
        echo "Commentaire : " . $dernier['commentaire'] . "\n";
    }

    echo "=== Test termine avec succes ===\n";

} catch (\Exception $e) {
    echo "ERREUR : " . $e->getMessage() . "\n";
}
