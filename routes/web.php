<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\CovoiturageController;
use App\Controllers\ProfilController;

/** @var \App\Core\Router $router */

$router->get('/', [HomeController::class, 'index']);

$router->get('/inscription', [AuthController::class, 'registerForm']);
$router->post('/inscription', [AuthController::class, 'register']);
$router->get('/connexion', [AuthController::class, 'loginForm']);
$router->post('/connexion', [AuthController::class, 'login']);
$router->get('/deconnexion', [AuthController::class, 'logout']);

$router->get('/covoiturages', [CovoiturageController::class, 'index']);
$router->get('/api/covoiturages', [CovoiturageController::class, 'api']);
$router->get('/covoiturage/{id}', [CovoiturageController::class, 'detail']);
$router->post('/covoiturage/{id}/participer', [CovoiturageController::class, 'participer']);

// Espace utilisateur
$router->get('/mon-espace', [ProfilController::class, 'index']);
use App\Controllers\HomeController;

/** @var \App\Core\Router $router */

$router->get('/', [HomeController::class, 'index']);
