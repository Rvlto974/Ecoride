<?php
declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    // Affiche une vue dans le layout principal
    protected function view(string $view, array $data = []): void
    {
        extract($data);

        // On capture le contenu de la vue dans une variable
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            throw new \RuntimeException("Vue introuvable : $view");
        }
        ob_start();
        require $viewPath;
        $contenu = ob_get_clean();

        // On injecte ce contenu dans le layout
        require __DIR__ . '/../Views/layouts/main.php';
    }

    // Verifie que l'utilisateur est connecte, sinon redirige vers la connexion
    protected function requireLogin(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /connexion');
            exit;
        }
    }

    // Verifie que l'utilisateur connecte possede un des roles autorises
    // $rolesAutorises : tableau, ex. ['administrateur'] ou ['administrateur', 'employe']
    protected function requireRole(array $rolesAutorises): void
    {
        // D'abord, il faut etre connecte
        $this->requireLogin();

        // Ensuite, son role doit faire partie des roles autorises
        if (!in_array($_SESSION['user']['role'], $rolesAutorises, true)) {
            // Acces interdit : on affiche une page 403
            http_response_code(403);
            $this->view('errors/403', ['titre' => 'Acces interdit']);
            exit;
        }
    }
}
