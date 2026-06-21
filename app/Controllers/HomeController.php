<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    // Affiche la page d'accueil
    public function index(): void
    {
        $this->view('home/index', [
            'titre' => 'EcoRide - Accueil',
        ]);
    }
}
