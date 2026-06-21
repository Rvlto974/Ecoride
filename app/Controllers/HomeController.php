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

    // Affiche la page des mentions legales (page statique)
    public function mentionsLegales(): void
    {
        $this->view('home/mentions', [
            'titre' => 'EcoRide - Mentions legales',
        ]);
    }
}
