<?php
declare(strict_types=1);

// Point d'entree unique de l'application (front controller)

// Chargement automatique des classes via Composer (PSR-4)
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;

// Demarrage de la session (necessaire pour l'authentification)
session_start();

// Creation du routeur et chargement des routes
$router = new Router();
require_once __DIR__ . '/../routes/web.php';

// Aiguillage de la requete vers le bon controleur
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
