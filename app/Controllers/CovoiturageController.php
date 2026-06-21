<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CovoiturageModel;

class CovoiturageController extends Controller
{
    // Affiche la liste des covoiturages (avec recherche optionnelle)
    public function index(): void
    {
        $covoiturageModel = new CovoiturageModel();

        // On recupere les criteres de recherche depuis l'URL (GET)
        $depart  = trim($_GET['depart'] ?? '');
        $arrivee = trim($_GET['arrivee'] ?? '');
        $date    = trim($_GET['date'] ?? '');

        // Si les 3 criteres sont remplis -> recherche filtree, sinon -> tout afficher
        if ($depart !== '' && $arrivee !== '' && $date !== '') {
            $covoiturages = $covoiturageModel->search($depart, $arrivee, $date);
        } else {
            $covoiturages = $covoiturageModel->findAll();
        }

        $this->view('covoiturages/index', [
            'titre' => 'EcoRide - Covoiturages',
            'covoiturages' => $covoiturages,
            // On renvoie les criteres pour pre-remplir le formulaire
            'depart' => $depart,
            'arrivee' => $arrivee,
            'date' => $date,
        ]);
    }

    // Point d'entree API : renvoie les covoiturages filtres en JSON (pour l'AJAX)
    public function api(): void
    {
        $covoiturageModel = new CovoiturageModel();

        // On recupere les filtres depuis l'URL (GET)
        $filtres = [
            'eco'        => $_GET['eco'] ?? '',
            'prix_max'   => $_GET['prix_max'] ?? '',
            'places_min' => $_GET['places_min'] ?? '',
        ];

        $covoiturages = $covoiturageModel->searchFiltered($filtres);

        // On indique au navigateur qu'on renvoie du JSON (et pas du HTML)
        header('Content-Type: application/json');

        // On convertit le tableau PHP en JSON et on l'affiche
        echo json_encode($covoiturages);
    }

    // Affiche le detail d'un covoiturage (route /covoiturage/{id})
    // Le parametre $id vient de l'URL, capture par le routeur
    public function detail(string $id): void
    {
        $covoiturageModel = new CovoiturageModel();
        $covoiturage = $covoiturageModel->findById((int) $id);

        // Si l'id n'existe pas -> page 404
        if ($covoiturage === null) {
            http_response_code(404);
            $this->view('errors/404', ['titre' => 'Covoiturage introuvable']);
            return;
        }

        // On recupere un eventuel message flash (succes/erreur de participation)
        $message = $_SESSION['message'] ?? null;
        unset($_SESSION['message']); // on l'efface apres lecture

        $this->view('covoiturages/detail', [
            'titre' => 'EcoRide - Detail du trajet',
            'covoiturage' => $covoiturage,
            'message' => $message,
        ]);
    }

    // Traite la participation a un covoiturage (POST /covoiturage/{id}/participer)
    public function participer(string $id): void
    {
        // Verification : l'utilisateur doit etre connecte
        if (!isset($_SESSION['user'])) {
            header('Location: /connexion');
            exit;
        }

        $covoiturageModel = new CovoiturageModel();

        // On appelle la methode participer du modele (transaction)
        $resultat = $covoiturageModel->participer(
            (int) $id,
            (int) $_SESSION['user']['id']
        );

        // Si succes : on met a jour les credits affiches dans la session
        if ($resultat['succes'] && isset($resultat['credits'])) {
            $_SESSION['user']['credits'] = $resultat['credits'];
        }

        // On stocke le message en session pour l'afficher apres redirection (flash)
        $_SESSION['message'] = $resultat['message'];

        // Redirection vers la page detail du trajet
        header('Location: /covoiturage/' . $id);
        exit;
    }
}
