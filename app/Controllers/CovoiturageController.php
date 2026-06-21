<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CovoiturageModel;

class CovoiturageController extends Controller
{
    // Affiche la liste des covoiturages disponibles
    public function index(): void
    {
        // On instancie le modele et on recupere tous les trajets
        $covoiturageModel = new CovoiturageModel();
        $covoiturages = $covoiturageModel->findAll();

        // On passe la liste a la vue
        $this->view('covoiturages/index', [
            'titre' => 'EcoRide - Covoiturages',
            'covoiturages' => $covoiturages,
        ]);
    }
}