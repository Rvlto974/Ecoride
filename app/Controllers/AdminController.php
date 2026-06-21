<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\StatsModel;

class AdminController extends Controller
{
    // Tableau de bord administrateur (statistiques)
    public function dashboard(): void
    {
        // Securite : reserve aux administrateurs uniquement
        // (la methode requireRole vient du Controller de base)
        $this->requireRole(['administrateur']);

        $statsModel = new StatsModel();

        // On recupere toutes les statistiques
        $this->view('admin/dashboard', [
            'titre' => 'EcoRide - Administration',
            'nbUtilisateurs'    => $statsModel->compterUtilisateurs(),
            'nbCovoiturages'    => $statsModel->compterCovoiturages(),
            'nbParticipations'  => $statsModel->compterParticipations(),
            'totalCredits'      => $statsModel->totalCredits(),
            'parJour'           => $statsModel->covoituragesParJour(),
        ]);
    }
}
