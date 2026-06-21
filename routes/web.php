<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;

/** @var \App\Core\Router $router */

$router->get('/', [HomeController::class, 'index']);

// Authentification (US7)
$router->get('/inscription', [AuthController::class, 'registerForm']);
$router->post('/inscription', [AuthController::class, 'register']);
$router->get('/connexion', [AuthController::class, 'loginForm']);
$router->post('/connexion', [AuthController::class, 'login']);
$router->get('/deconnexion', [AuthController::class, 'logout']);