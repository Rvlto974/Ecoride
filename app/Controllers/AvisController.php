<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\AvisModel;
use App\Models\CovoiturageModel;

class AvisController extends Controller
{
    // Affiche le formulaire pour laisser un avis sur un trajet
    // $id = identifiant du covoiturage concerne
    public function formulaire(string $id): void
    {
        // Securite : reserve aux utilisateurs connectes
        $this->requireLogin();

        $covoiturageModel = new CovoiturageModel();
        $covoiturage = $covoiturageModel->findById((int) $id);

        // Si le trajet n'existe pas -> 404
        if ($covoiturage === null) {
            http_response_code(404);
            $this->view('errors/404', ['titre' => 'Trajet introuvable']);
            return;
        }

        $this->view('avis/formulaire', [
            'titre' => 'EcoRide - Laisser un avis',
            'covoiturage' => $covoiturage,
        ]);
    }

    // Enregistre l'avis dans MongoDB
    public function enregistrer(string $id): void
    {
        $this->requireLogin();

        $covoiturageModel = new CovoiturageModel();
        $covoiturage = $covoiturageModel->findById((int) $id);

        if ($covoiturage === null) {
            http_response_code(404);
            $this->view('errors/404', ['titre' => 'Trajet introuvable']);
            return;
        }

        // Recuperation des donnees du formulaire
        $note = (int) ($_POST['note'] ?? 0);
        $commentaire = trim($_POST['commentaire'] ?? '');

        $erreurs = [];

        // Validation : note entre 1 et 5
        if ($note < 1 || $note > 5) {
            $erreurs[] = 'La note doit etre comprise entre 1 et 5.';
        }
        if ($commentaire === '') {
            $erreurs[] = 'Le commentaire est obligatoire.';
        }

        // S'il y a des erreurs, on reaffiche le formulaire
        if (!empty($erreurs)) {
            $this->view('avis/formulaire', [
                'titre' => 'EcoRide - Laisser un avis',
                'covoiturage' => $covoiturage,
                'erreurs' => $erreurs,
            ]);
            return;
        }

        // Enregistrement de l'avis dans MongoDB (statut "en_attente")
        $avisModel = new AvisModel();
        $avisModel->creer([
            'id_passager'      => $_SESSION['user']['id'],
            'pseudo_passager'  => $_SESSION['user']['pseudo'],
            // Le chauffeur est l'auteur du trajet
            'id_chauffeur'     => (int) $covoiturage['id_utilisateur'],
            'pseudo_chauffeur' => $covoiturage['chauffeur'],
            'note'             => $note,
            'commentaire'      => $commentaire,
        ]);

        // Message + redirection vers l'espace utilisateur
        $_SESSION['message'] = 'Votre avis a ete envoye. Il sera publie apres moderation.';
        header('Location: /mon-espace');
        exit;
    }
}
