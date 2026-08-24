<?php

namespace App\Service;

use App\Controller\HomeController;
use App\Controller\BookController;
use App\Controller\AuthController;
use Exception;

class Router extends Singleton
{
    //Lance le router 
    public function run(): void
    {
        $route = $_GET['route'] ?? '';

        switch ($route) {
            case '':
                (new HomeController())->index();
                break;

            case 'books':
                (new BookController())->index();
                break;

            case 'book-details':
                (new BookController())->show();
                break;

            case 'book-add':
                (new BookController())->create();
                 break;

            case 'book-edit':
                (new BookController())->edit();
                break;

            case 'register':
                (new AuthController())->register();
                break;
            
            case 'login':
                (new AuthController())->login();
                break;

            case 'logout':
                (new AuthController())->logout();
                break;

            default:
                throw new Exception('Page not found.');
        }
    }
}