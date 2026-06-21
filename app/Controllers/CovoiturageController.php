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
}