<?php

//Charge l'autoload de l'application
require_once __DIR__ . '/../src/Config/Autoload.php';

use App\Config\Autoload;
use App\Controller\HomeController;

//Enregistre l'autoloader PSR-4
Autoload::register();

//Lance le contrôleur de la page d'accueil
$controller = new HomeController();
$controller->index();