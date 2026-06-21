<?php
declare(strict_types=1);

namespace App\Core;

class Router
{
    // Tableau des routes enregistrees, classe par methode HTTP (GET, POST)
    private array $routes = [];

    // Enregistre une route GET
    public function get(string $path, array $action): void
    {
        $this->add('GET', $path, $action);
    }

    // Enregistre une route POST
    public function post(string $path, array $action): void
    {
        $this->add('POST', $path, $action);
    }

    // Ajoute une route dans le tableau
    private function add(string $method, string $path, array $action): void
    {
        $this->routes[$method][$this->normalize($path)] = $action;
    }

    // Normalise un chemin : toujours un seul / au debut, pas de / a la fin
    private function normalize(string $path): string
    {
        return '/' . trim($path, '/');
    }

    // Aiguille la requete vers le bon controleur selon l'URL et la methode
    public function dispatch(string $uri, string $method): void
    {
        // On isole le chemin (sans les parametres ?cle=valeur)
        $path = $this->normalize(parse_url($uri, PHP_URL_PATH) ?? '/');

        // On parcourt les routes enregistrees pour la methode HTTP demandee
        foreach ($this->routes[$method] ?? [] as $route => $action) {
            // On transforme une route type /covoiturage/{id} en expression reguliere :
            // {id} devient un groupe de capture ([^/]+) qui attrape la valeur
            $pattern = preg_replace('#\{[a-z]+\}#', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            // Si l'URL demandee correspond a ce motif
            if (preg_match($pattern, $path, $matches)) {
                // On retire la correspondance complete, on garde les parametres captures
                array_shift($matches);

                [$controllerClass, $methodName] = $action;
                // On instancie le controleur et on appelle sa methode
                // en lui passant les parametres de l'URL (ex : l'id)
                (new $controllerClass())->$methodName(...$matches);
                return;
            }
        }

        // Aucune route ne correspond -> 404
        http_response_code(404);
        echo '404 - Page introuvable';
    }
}
