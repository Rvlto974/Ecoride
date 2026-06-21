<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $host = getenv('DB_HOST') ?: 'mysql';
            $db   = getenv('DB_NAME') ?: 'ecoride';
            $user = getenv('DB_USER') ?: 'ecoride';
            $pass = getenv('DB_PASS') ?: 'ecoride_pass';

            $dsn = "mysql:host={$host};dbname={$db};charset=utf8mb4";

            try {
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                die('Erreur de connexion MySQL : ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
