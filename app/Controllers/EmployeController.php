<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\AvisModel;

class EmployeController extends Controller
{
    // Espace employe : moderation des avis en attente
    public function moderation(): void
    {
        // Securite : reserve aux employes ET administrateurs
        $this->requireRole(['employe', 'administrateur']);

        $avisModel = new AvisModel();

        // On recupere les avis en attente de validation
        $this->view('employe/moderation', [
            'titre' => 'EcoRide - Moderation des avis',
            'avis' => $avisModel->findEnAttente(),
        ]);
    }

    // Valide un avis (le rend public)
    public function valider(string $id): void
    {
        $this->requireRole(['employe', 'administrateur']);

        $avisModel = new AvisModel();
        $avisModel->changerStatut($id, 'valide');

        $_SESSION['message'] = 'Avis valide.';
        header('Location: /employe/moderation');
        exit;
    }

    // Refuse un avis (il ne sera pas affiche)
    public function refuser(string $id): void
    {
        $this->requireRole(['employe', 'administrateur']);

        $avisModel = new AvisModel();
        $avisModel->changerStatut($id, 'refuse');

        $_SESSION['message'] = 'Avis refuse.';
        header('Location: /employe/moderation');
        exit;
    }
}
