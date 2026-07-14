<?php

namespace App\Service;

use Exception;
use PDO;
use PDOException;

class Database extends Singleton
{
    //Connexion PDO unique
    private ?PDO $connection = null;

    //Retourne une connexion PDO unique
    public function getConnection(): PDO
    {
        if ($this->connection === null) {
           $this->connection = $this->createConnection();
        }

        return $this->connection;
    }

    //Création de la connexion à la base de données
    private function createConnection(): PDO
    {
        $config = Config::getInstance();

        //Permet de récupérer les informations de connexion à la base de données
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
