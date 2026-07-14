<?php

namespace App\Service;

use App\Controller\HomeController;
use App\Controller\BookController;
use Exeception;

class Router 
{
    //Lanace le router 
    public function run(): void
    {
        $route = $_GET['route'] ?? '';

        switch ($route) {
            case'':
                (new HomeController())->index();
                break;

            case 'books':
                (new BookController())->index();
                break;

            default:
                throw new Exception('Page not found.');
        }
    }
}