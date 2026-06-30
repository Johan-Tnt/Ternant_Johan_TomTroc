<?php

namespace App\Config;

class Autoload
{
    //Enregistre l'autoload
    public static function register(): void
    {
        spl_autoload_register([self::class, 'load']);
    }

    //Charge automatiquement les classes
    private static function load(string $class): void
    {
        //Charge que les classes du projet
        if (!str_starts_with($class, 'App\\')) {
            return;
        }

        //Retire "App\"
        $class = str_replace('App\\', '', $class);

        //Transforme les "\" en "/"
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

        //Construit le chemin vers le fichier
        $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . $class . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
}