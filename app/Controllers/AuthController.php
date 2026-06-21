<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;

class AuthController extends Controller
{
    // Affiche le formulaire d'inscription
    public function registerForm(): void
    {
        $this->view('auth/inscription', ['titre' => 'EcoRide - Inscription']);
    }

    // Traite l'inscription
    public function register(): void
    {
        $pseudo = trim($_POST['pseudo'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $motDePasse = $_POST['mot_de_passe'] ?? '';

        $erreurs = [];

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
        if (empty($erreurs) && $userModel->emailExists($email)) {
            $erreurs[] = 'Cet email est deja utilise.';
        }

        if (!empty($erreurs)) {
            $this->view('auth/inscription', [
                'titre' => 'EcoRide - Inscription',
                'erreurs' => $erreurs,
                'pseudo' => $pseudo,
                'email' => $email,
            ]);
            return;
        }

        $userModel->create($pseudo, $email, $motDePasse);
        header('Location: /connexion');
        exit;
    }

    // Affiche le formulaire de connexion
    public function loginForm(): void
    {
        $this->view('auth/connexion', ['titre' => 'EcoRide - Connexion']);
    }

    // Traite la connexion
    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $motDePasse = $_POST['mot_de_passe'] ?? '';

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        // Verification email + mot de passe (message volontairement vague pour la securite)
        if ($user === null || !password_verify($motDePasse, $user['mot_de_passe'])) {
            $this->view('auth/connexion', [
                'titre' => 'EcoRide - Connexion',
                'erreurs' => ['Email ou mot de passe incorrect.'],
                'email' => $email,
            ]);
            return;
        }

        // Verification du statut : un compte suspendu ne peut pas se connecter
        if ($user['statut'] === 'suspendu') {
            $this->view('auth/connexion', [
                'titre' => 'EcoRide - Connexion',
                'erreurs' => ['Votre compte a ete suspendu. Contactez l administration.'],
                'email' => $email,
            ]);
            return;
        }

        // Connexion reussie : on stocke les infos utiles en session
        $_SESSION['user'] = [
            'id' => $user['id_utilisateur'],
            'pseudo' => $user['pseudo'],
            'role' => $user['role'],
            'credits' => $user['credits'],
        ];
        header('Location: /');
        exit;
    }

    // Deconnexion
    public function logout(): void
    {
        session_destroy();
        header('Location: /');
        exit;
    }
}
