<?php

class Database
{
    private static ?PDO $connection = null;

    //Retourne une connexion PDO unique
    public static function getConnection(): PDO
    {
        //Si aucune connexion n’existe encore, elle est créée à partir du fichier .env
        if (self::$connection === null) {
            $config = self::loadEnv();
            
            //Permet de récupéré les informations de connexion à la base de données
            $host = $config['DB_HOST'] ?? 'localhost'; 
            $dbname = $config['DB_NAME'] ?? '';
            $user = $config['DB_USER'] ?? '';
            $password = $config['DB_PASSWORD'] ?? '';

            //Construction du DSN pour la connexion MySQL
            $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

            //Création de la connexion PDO avec les options de gestion des erreurs
            try {
                self::$connection = new PDO($dsn, $user, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $exception) {
                throw new Exception('Database connection failed: ' . $exception->getMessage());
            }
        }
        return self::$connection;
    }

    //Charge les variables du fichier .env
    private static function loadEnv(): array 
    {
        $envPath = dirname(__DIR__, 2) . '/.env';

        //Permet de vérifié que le fichier .env existe
        if (!file_exists($envPath)) {
            throw new Exception('.env file not found');
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $config = [];

        foreach ($lines as $line) {
            $line = trim($line); 

            //Ignore les lignes vides et les commentaires
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            //Sépare chaque ligne en clé/valeur
            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');

            //Stocke la configuration dans un tableau associatif
            $config[trim($key)] = trim($value);
        }
        return $config;
    }
}
