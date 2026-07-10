<?php

namespace App\Service;

use Exception;

class Config
{
    //Instance unique de Config
    private static ?Config $instance = null;

    //Contient les variables du fichier .env
    private ?array $config = null;

    //Empêche l'instanciation directe
    private function __construct()
    {
    }

    //Retourne l'instance unique
    public static function getInstance(): Config
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

    //Retourne toute la configuration
    public function getAll(): array
    {
        if ($this->config === null) {
            $this->loadEnv();
        }

        return $this->config;
    }

    //Retourne une valeur de configuration
    public function get(string $key): mixed
    {
        if ($this->config === null) {
            $this->loadEnv();
        }

        return $this->config[$key] ?? null;
    }

    //Charge le fichier .env
    private function loadEnv(): void
    {
        $envPath = dirname(__DIR__, 2) . '/.env';

        if (!file_exists($envPath)) {
            throw new Exception('.env file not found.');
        }

        $this->config = [];

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {

            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');

            $this->config[trim($key)] = trim($value);
        }
    }
}