<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\VehiculeModel;

class VehiculeController extends Controller
{
    // Affiche le formulaire d'ajout de vehicule
    public function ajouterForm(): void
    {
        // Securite : reserve aux utilisateurs connectes
        if (!isset($_SESSION['user'])) {
            header('Location: /connexion');
            exit;
        }

        $vehiculeModel = new VehiculeModel();

        $this->view('vehicules/ajouter', [
            'titre' => 'EcoRide - Ajouter un vehicule',
            // On envoie la liste des marques pour le menu deroulant
            'marques' => $vehiculeModel->findAllMarques(),
        ]);
    }

    // Traite l'enregistrement du vehicule
    public function ajouter(): void
    {
        // Securite : reserve aux connectes
        if (!isset($_SESSION['user'])) {
            header('Location: /connexion');
            exit;
        }

        // Recuperation des donnees du formulaire
        $data = [
            'immatriculation'     => trim($_POST['immatriculation'] ?? ''),
            'date_premiere_immat' => trim($_POST['date_premiere_immat'] ?? ''),
            'modele'              => trim($_POST['modele'] ?? ''),
            'couleur'             => trim($_POST['couleur'] ?? ''),
            'nb_places'           => $_POST['nb_places'] ?? '',
            'energie'             => trim($_POST['energie'] ?? ''),
            'id_marque'           => $_POST['id_marque'] ?? '',
        ];

        $erreurs = [];

        // Validations simples
        if ($data['immatriculation'] === '') {
            $erreurs[] = 'L immatriculation est obligatoire.';
        }
        if ($data['modele'] === '') {
            $erreurs[] = 'Le modele est obligatoire.';
        }
        if ((int) $data['nb_places'] < 1) {
            $erreurs[] = 'Le nombre de places doit etre au moins 1.';
        }
        if ($data['energie'] === '') {
            $erreurs[] = 'L energie est obligatoire.';
        }
        if ((int) $data['id_marque'] < 1) {
            $erreurs[] = 'La marque est obligatoire.';
        }

        $vehiculeModel = new VehiculeModel();

        // S'il y a des erreurs, on reaffiche le formulaire
        if (!empty($erreurs)) {
            $this->view('vehicules/ajouter', [
                'titre' => 'EcoRide - Ajouter un vehicule',
                'marques' => $vehiculeModel->findAllMarques(),
                'erreurs' => $erreurs,
                'data' => $data,
            ]);
            return;
        }

        // Creation du vehicule
        $vehiculeModel->create($data, (int) $_SESSION['user']['id']);

        // Message + redirection vers l'espace utilisateur
        $_SESSION['message'] = 'Vehicule ajoute avec succes.';
        header('Location: /mon-espace');
        exit;
    }
}