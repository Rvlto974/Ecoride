<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CovoiturageModel;

class ProfilController extends Controller
{
    // Affiche l'espace utilisateur : profil + historique des participations
    public function index(): void
    {
        // Securite : page reservee aux utilisateurs connectes
        if (!isset($_SESSION['user'])) {
            header('Location: /connexion');
            exit;
        }

        $covoiturageModel = new CovoiturageModel();

        // On recupere les trajets auxquels l'utilisateur participe
        $participations = $covoiturageModel->findParticipations(
            (int) $_SESSION['user']['id']
        );

        $this->view('profil/index', [
            'titre' => 'EcoRide - Mon espace',
            'participations' => $participations,
        ]);
    }
}