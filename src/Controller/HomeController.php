<?php

namespace App\Controller;

use App\Service\View;
class HomeController
{
    public function index(): void
    {
        View::getInstance()->render('home', 'Accueil');
    }
}