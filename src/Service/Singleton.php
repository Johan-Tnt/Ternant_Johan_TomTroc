<?php

namespace App\Service;

use Exception;

//Classe de base pour créer un Singleton
abstract class Singleton
{
    //Stocke l'instance unique de chaque classe héritée (Config, Database, View, etc)
    private static array $instances = [];

    //Empêche la création directe de l'objet
    protected function __construct()
    {
    }

    //Retourne l'instance unique
    public static function getInstance(): static
    {
        $class = static::class;

        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }

        return self::$instances[$class];
    }

    //Empêche la copie de l'objet
    private function __clone()
    {
    }

    //Empêche la restauration de l'objet
    public function __wakeup()
    {
        throw new Exception('Cannot restore singleton.');
    }
}