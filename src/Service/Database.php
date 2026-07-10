<?php

namespace App\Service;

use Exception;
use PDO;
use PDOException;

class Database
{
    //Instance unique de Database
    private static ?Database $instance = null;

    //Connexion PDO unique
    private ?PDO $connection = null;

    //Empêche l'instanciation directe
    private function __construct()
    {
    }

    //Retourne l'instance unique
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
           self::$instance = new self();
        }

        return self::$instance;
    }
    
   //Empêche de copier l'instance
    private function __clone()
    {
    }

    //Empêche de recréer l'instance
    public function __wakeup()
    {
        throw new Exception('Cannot recreate this instance.');
    }

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
