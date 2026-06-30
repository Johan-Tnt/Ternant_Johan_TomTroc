<?php

namespace App\Service;

use Exception;
use PDO;
use PDOException;
class Database
{
    private static ?PDO $connection = null;

    //Retourne une connexion PDO unique
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
              self::$connection = self::createConnection();
        }
        return self::$connection;
    }

    //Création de la connexion à la base de données
    private static function createConnection(): PDO
    {
        $config = new Config();

        //Permet de récupéré les informations de connexion à la base de données
        $host = $config->get('DB_HOST');
        $dbname = $config->get('DB_NAME');
        $user = $config->get('DB_USER');
        $password = $config->get('DB_PASSWORD');

        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

        try {
            return new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            throw new Exception(
                'Database connection failed: ' . $exception->getMessage()
            );
        }
    }
}
