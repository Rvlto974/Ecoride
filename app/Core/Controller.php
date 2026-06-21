<?php
declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
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
}