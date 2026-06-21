<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\StatsModel;
use App\Models\UserModel;

class AdminController extends Controller
{
    // Tableau de bord administrateur (statistiques)
    public function dashboard(): void
    {
        $this->requireRole(['administrateur']);

        $statsModel = new StatsModel();

        $this->view('admin/dashboard', [
            'titre' => 'EcoRide - Administration',
            'nbUtilisateurs'    => $statsModel->compterUtilisateurs(),
            'nbCovoiturages'    => $statsModel->compterCovoiturages(),
            'nbParticipations'  => $statsModel->compterParticipations(),
            'totalCredits'      => $statsModel->totalCredits(),
            'parJour'           => $statsModel->covoituragesParJour(),
        ]);
    }

    // Liste de tous les comptes (gestion)
    public function comptes(): void
    {
        $this->requireRole(['administrateur']);

        $userModel = new UserModel();

        $this->view('admin/comptes', [
            'titre' => 'EcoRide - Gestion des comptes',
            'comptes' => $userModel->findAll(),
        ]);
    }

    // Affiche le formulaire de creation d'un compte employe
    public function creerEmployeForm(): void
    {
        $this->requireRole(['administrateur']);

        $this->view('admin/creer_employe', [
            'titre' => 'EcoRide - Creer un employe',
        ]);
    }

    // Traite la creation d'un compte employe
    public function creerEmploye(): void
    {
        $this->requireRole(['administrateur']);

        $pseudo = trim($_POST['pseudo'] ?? '');
        $email  = trim($_POST['email'] ?? '');
        $motDePasse = $_POST['mot_de_passe'] ?? '';

        $erreurs = [];

        // Validations
        if ($pseudo === '') {
            $erreurs[] = 'Le pseudo est obligatoire.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = 'L email n est pas valide.';
        }
        if (strlen($motDePasse) < 8) {
            $erreurs[] = 'Le mot de passe doit faire au moins 8 caracteres.';
        }

        $userModel = new UserModel();

        // Verifie que l'email n'existe pas deja
        if (empty($erreurs) && $userModel->emailExists($email)) {
            $erreurs[] = 'Cet email est deja utilise.';
        }

        // S'il y a des erreurs, on reaffiche le formulaire
        if (!empty($erreurs)) {
            $this->view('admin/creer_employe', [
                'titre' => 'EcoRide - Creer un employe',
                'erreurs' => $erreurs,
                'pseudo' => $pseudo,
                'email' => $email,
            ]);
            return;
        }

        // Creation de l'employe
        $userModel->creerEmploye($pseudo, $email, $motDePasse);

        $_SESSION['message'] = 'Compte employe cree avec succes.';
        header('Location: /admin/comptes');
        exit;
    }

    // Suspend un compte
    public function suspendre(string $id): void
    {
        $this->requireRole(['administrateur']);

        $userModel = new UserModel();
        $userModel->changerStatut((int) $id, 'suspendu');

        $_SESSION['message'] = 'Compte suspendu.';
        header('Location: /admin/comptes');
        exit;
    }

    // Reactive un compte
    public function reactiver(string $id): void
    {
        $this->requireRole(['administrateur']);

        $userModel = new UserModel();
        $userModel->changerStatut((int) $id, 'actif');

        $_SESSION['message'] = 'Compte reactive.';
        header('Location: /admin/comptes');
        exit;
    }
}
