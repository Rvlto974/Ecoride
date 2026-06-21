<?php
declare(strict_types=1);

namespace App\Core;

use MongoDB\Client;

class Mongo
{
    // Instance unique de la connexion (pattern singleton)
    private static ?Client $client = null;

    // Retourne la base de donnees MongoDB "ecoride"
    public static function getDatabase()
    {
        // On cree la connexion une seule fois
        if (self::$client === null) {
            // L'URI pointe vers le conteneur mongo (nom du service Docker)
            $uri = 'mongodb://mongo:27017';
            self::$client = new Client($uri);
        }

        // On retourne la base "ecoride"
        return self::$client->selectDatabase('ecoride');
    }
}