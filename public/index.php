<?php

//Démarre la session utilisateur
session_start();

//Charge l'autoload de l'application
require_once __DIR__ . '/../src/Config/Autoload.php';

use App\Config\Autoload;
use App\Service\Router;

//Enregistre l'autoloader PSR-4
Autoload::register();

//Lance le router
Router::getInstance()->run();