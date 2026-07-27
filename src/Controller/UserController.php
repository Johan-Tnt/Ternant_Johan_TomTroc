<?php

namespace App\Controller;

use App\Service\View;

class UserController{
    //Affiche la page d'inscription du site
    public function  register(): void 
    {
        View::getInstance()->render(
            'register',
            'Inscription'
        );
    }
}