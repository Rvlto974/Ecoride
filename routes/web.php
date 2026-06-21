<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\CovoiturageController;

/** @var \App\Core\Router $router */

$router->get('/', [HomeController::class, 'index']);

$router->get('/inscription', [AuthController::class, 'registerForm']);
$router->post('/inscription', [AuthController::class, 'register']);
$router->get('/connexion', [AuthController::class, 'loginForm']);
$router->post('/connexion', [AuthController::class, 'login']);
$router->get('/deconnexion', [AuthController::class, 'logout']);

// Liste des covoiturages
$router->get('/covoiturages', [CovoiturageController::class, 'index']);

// API JSON pour les filtres AJAX
$router->get('/api/covoiturages', [CovoiturageController::class, 'api']);