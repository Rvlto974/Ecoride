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
        $this->view('auth/inscription', [
            'titre' => 'EcoRide - Inscription',
        ]);
    }

    // Affiche le formulaire de connexion
    public function loginForm(): void
    {
        $this->view('auth/connexion', [
            'titre' => 'EcoRide - Connexion',
        ]);
    }
}
