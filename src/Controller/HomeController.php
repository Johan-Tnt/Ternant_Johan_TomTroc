<?php

class HomeController
{
    public function index(): void
    {
        $view = new View('Accueil');
        $view->render('home');
    }
}