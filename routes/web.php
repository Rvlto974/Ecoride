<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\CovoiturageController;
use App\Controllers\ProfilController;
use App\Controllers\VehiculeController;
use App\Controllers\VoyageController;
use App\Controllers\AdminController;
use App\Controllers\EmployeController;
use App\Controllers\AvisController;

/** @var \App\Core\Router $router */

$router->get('/', [HomeController::class, 'index']);

// Authentification
$router->get('/inscription', [AuthController::class, 'registerForm']);
$router->post('/inscription', [AuthController::class, 'register']);
$router->get('/connexion', [AuthController::class, 'loginForm']);
$router->post('/connexion', [AuthController::class, 'login']);
$router->get('/deconnexion', [AuthController::class, 'logout']);

// Covoiturages
$router->get('/covoiturages', [CovoiturageController::class, 'index']);
$router->get('/api/covoiturages', [CovoiturageController::class, 'api']);
$router->get('/covoiturage/{id}', [CovoiturageController::class, 'detail']);
$router->post('/covoiturage/{id}/participer', [CovoiturageController::class, 'participer']);

// Espace utilisateur
$router->get('/mon-espace', [ProfilController::class, 'index']);

// Gestion des vehicules (chauffeur)
$router->get('/vehicule/ajouter', [VehiculeController::class, 'ajouterForm']);
$router->post('/vehicule/ajouter', [VehiculeController::class, 'ajouter']);

// Saisie de voyage (chauffeur)
$router->get('/voyage/creer', [VoyageController::class, 'creerForm']);
$router->post('/voyage/creer', [VoyageController::class, 'creer']);

// Avis (laisser un avis sur un trajet)
$router->get('/avis/{id}', [AvisController::class, 'formulaire']);
$router->post('/avis/{id}/enregistrer', [AvisController::class, 'enregistrer']);

// Espace administrateur
$router->get('/admin', [AdminController::class, 'dashboard']);

// Espace employe : moderation des avis
$router->get('/employe/moderation', [EmployeController::class, 'moderation']);
$router->post('/employe/avis/{id}/valider', [EmployeController::class, 'valider']);
$router->post('/employe/avis/{id}/refuser', [EmployeController::class, 'refuser']);
