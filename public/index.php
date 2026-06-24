<?php

require_once __DIR__ . '/../src/Controller/HomeController.php';

$controller = new HomeController();
$controller->index();

//Test de la connexion à la base de données 
require_once __DIR__ . '/../src/Service/Database.php';

try {
    $pdo = Database::getConnection();
    echo 'Connexion à la base de données réussie.';
} catch (Exception $exception) {
    echo $exception->getMessage();
}