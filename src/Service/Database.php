<?php

class Database
{
    private static ?PDO $connection = null;

    //Retourne une connexion PDO unique
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $config = self::loadEnv();
            
            $host = $config['DB_HOST'] ?? 'localhost'; 
            $dbname = $config['DB_NAME'] ?? '';
            $user = $config['DB_USER'] ?? '';
            $password = $config['DB_PASSWORD'] ?? '';

            $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
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

        if (!file_exists($envPath)) {
            throw new Exception('.env file not found');
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $config = [];

        foreach ($lines as $line) {
            $line = trim($line); 

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');

            $config[trim($key)] = trim($value);
        }
        return $config;
    }
}
