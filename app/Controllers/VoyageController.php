<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CovoiturageModel;
use App\Models\VehiculeModel;

class VoyageController extends Controller
{
    // Affiche le formulaire de saisie d'un voyage
    public function creerForm(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /connexion');
            exit;
        }

        $vehiculeModel = new VehiculeModel();
        $vehicules = $vehiculeModel->findByUser((int) $_SESSION['user']['id']);

        $this->view('voyages/creer', [
            'titre' => 'EcoRide - Proposer un voyage',
            'vehicules' => $vehicules,
        ]);
    }

    // Traite l'enregistrement du voyage
    public function creer(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /connexion');
            exit;
        }

        $data = [
            'ville_depart'  => trim($_POST['ville_depart'] ?? ''),
            'ville_arrivee' => trim($_POST['ville_arrivee'] ?? ''),
            'depart'        => trim($_POST['depart'] ?? ''),
            'arrivee'       => trim($_POST['arrivee'] ?? ''),
            'prix'          => $_POST['prix'] ?? '',
            'nb_places'     => $_POST['nb_places'] ?? '',
            'id_vehicule'   => $_POST['id_vehicule'] ?? '',
        ];

        $erreurs = [];

        if ($data['ville_depart'] === '') {
            $erreurs[] = 'La ville de depart est obligatoire.';
        }
        if ($data['ville_arrivee'] === '') {
            $erreurs[] = 'La ville d arrivee est obligatoire.';
        }
        if ($data['depart'] === '') {
            $erreurs[] = 'La date et heure de depart sont obligatoires.';
        }
        if ($data['arrivee'] === '') {
            $erreurs[] = 'La date et heure d arrivee sont obligatoires.';
        }
        if ((int) $data['nb_places'] < 1) {
            $erreurs[] = 'Le nombre de places doit etre au moins 1.';
        }
        if ((int) $data['id_vehicule'] < 1) {
            $erreurs[] = 'Vous devez choisir un vehicule.';
        }

        $vehiculeModel = new VehiculeModel();
        $covoiturageModel = new CovoiturageModel();

        if (!empty($erreurs)) {
            $this->view('voyages/creer', [
                'titre' => 'EcoRide - Proposer un voyage',
                'vehicules' => $vehiculeModel->findByUser((int) $_SESSION['user']['id']),
                'erreurs' => $erreurs,
                'data' => $data,
            ]);
            return;
        }

        $covoiturageModel->creer($data, (int) $_SESSION['user']['id']);

        $_SESSION['message'] = 'Votre voyage a ete propose avec succes.';
        header('Location: /mon-espace');
        exit;
    }
}