<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CovoiturageModel;
use App\Models\AvisModel;

class CovoiturageController extends Controller
{
    // Affiche la liste des covoiturages (avec recherche optionnelle)
    public function index(): void
    {
        $covoiturageModel = new CovoiturageModel();

        $depart  = trim($_GET['depart'] ?? '');
        $arrivee = trim($_GET['arrivee'] ?? '');
        $date    = trim($_GET['date'] ?? '');

        if ($depart !== '' && $arrivee !== '' && $date !== '') {
            $covoiturages = $covoiturageModel->search($depart, $arrivee, $date);
        } else {
            $covoiturages = $covoiturageModel->findAll();
        }

        $this->view('covoiturages/index', [
            'titre' => 'EcoRide - Covoiturages',
            'covoiturages' => $covoiturages,
            'depart' => $depart,
            'arrivee' => $arrivee,
            'date' => $date,
        ]);
    }

    // Point d'entree API : renvoie les covoiturages filtres en JSON (pour l'AJAX)
    public function api(): void
    {
        $covoiturageModel = new CovoiturageModel();

        $filtres = [
            'eco'        => $_GET['eco'] ?? '',
            'prix_max'   => $_GET['prix_max'] ?? '',
            'places_min' => $_GET['places_min'] ?? '',
        ];

        $covoiturages = $covoiturageModel->searchFiltered($filtres);

        header('Content-Type: application/json');
        echo json_encode($covoiturages);
    }

    // Affiche le detail d'un covoiturage (route /covoiturage/{id})
    public function detail(string $id): void
    {
        $covoiturageModel = new CovoiturageModel();
        $covoiturage = $covoiturageModel->findById((int) $id);

        if ($covoiturage === null) {
            http_response_code(404);
            $this->view('errors/404', ['titre' => 'Covoiturage introuvable']);
            return;
        }

        // On recupere les avis VALIDES du chauffeur + sa note moyenne (depuis MongoDB)
        $avisModel = new AvisModel();
        $idChauffeur = (int) $covoiturage['id_utilisateur'];
        $avis = $avisModel->findValidesByChauffeur($idChauffeur);
        $moyenne = $avisModel->moyenneChauffeur($idChauffeur);

        // Message flash eventuel (succes/erreur de participation)
        $message = $_SESSION['message'] ?? null;
        unset($_SESSION['message']);

        $this->view('covoiturages/detail', [
            'titre' => 'EcoRide - Detail du trajet',
            'covoiturage' => $covoiturage,
            'message' => $message,
            'avis' => $avis,
            'moyenne' => $moyenne,
        ]);
    }

    // Traite la participation a un covoiturage (POST /covoiturage/{id}/participer)
    public function participer(string $id): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /connexion');
            exit;
        }

        $covoiturageModel = new CovoiturageModel();

        $resultat = $covoiturageModel->participer(
            (int) $id,
            (int) $_SESSION['user']['id']
        );

        if ($resultat['succes'] && isset($resultat['credits'])) {
            $_SESSION['user']['credits'] = $resultat['credits'];
        }

        $_SESSION['message'] = $resultat['message'];

        header('Location: /covoiturage/' . $id);
        exit;
    }
}
