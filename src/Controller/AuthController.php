<?php

namespace App\Controller;

use App\Service\View;

class AuthController
{
    //Affiche la page de connexion
    public function login(): void
    {
        View::getInstance()->render(
            'login',
            'Connexion'
        );
    }
}