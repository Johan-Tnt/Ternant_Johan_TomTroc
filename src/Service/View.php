<?php

//Génère les vues en fonction de ce que chaque contrôleur lui passe en paramètre
class View
{
    //Titre de la page
    private string $title;

    //Constructeur
    public function __construct(string $title)
    {
        $this->title = $title;
    }

    //Génère une page complete à partir d'une vue et du template principal
    public function render(string $viewName, array $params = []): void
    {
        //Construction du chemin vers la vue demandée
        $viewPath = $this->buildViewPath($viewName);

        //Variables utilisées dans main.php
        $content = $this->renderView($viewPath, $params);
        $title = $this->title;

        ob_start();
        require MAIN_VIEW_PATH;
        echo ob_get_clean();
    }

    //Génère le contenu de la vue demandée
    private function renderView(string $viewPath, array $params = []): string
    {
        if (!file_exists($viewPath)) {
            throw new Exception("la vue '$viewPath' est introuvable.");
        }

        //Transforme les clés du tableau $params en variables utilisables dans la vue
        extract($params);

        ob_start();
        require $viewPath;
        return ob_get_clean();
    }

    //Construit le chemin complet vers la vue demandée
    private function buildViewPath(string $viewName): string {
        return VIEW_PATH . $viewName . '.php';
    }
}