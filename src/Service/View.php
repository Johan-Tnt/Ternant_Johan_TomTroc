<?php

namespace App\Service;

use Exception;
//Génère les vues en fonction de ce que chaque contrôleur lui passe en paramètre
class View
{
    //Chemin vers les vues
    private const VIEW_PATH = __DIR__ . '/../View/';

    //Chemin vers le layout principal
    private const LAYOUT_PATH = __DIR__ . '/../View/Layout/main.php';

    //Instance unique de View
    private static ?View $instance = null;

    //Empêche l'instanciation directe
    private function __construct()
    {
    }

    //Retourne l'instance unique
    public static function getInstance(): View
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

    //Génère une page complete à partir d'une vue et du layout principal
    public function render(string $viewName, string $title, array $params = []): void
    {
        //Construction du chemin vers la vue demandée
        $viewPath = $this->buildViewPath($viewName);

        //Variables utilisées dans main.php
        $content = $this->renderView($viewPath, $params);

        ob_start();
        require self::LAYOUT_PATH;
        echo ob_get_clean();
    }

    //Génère le contenu de la vue demandée
    private function renderView(string $viewPath, array $params = []): string
    {
        if (!file_exists($viewPath)) {
            throw new Exception("La vue '$viewPath' est introuvable.");
        }

        //Transforme les clés du tableau $params en variables utilisables dans la vue
        extract($params);

        ob_start();
        require $viewPath;
        return ob_get_clean();
    }

    //Construit le chemin complet vers la vue demandée
    private function buildViewPath(string $viewName): string 
    {
        return self::VIEW_PATH . $viewName . '.php';
    }
}