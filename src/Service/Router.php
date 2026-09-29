<?php

namespace App\Service;

use App\Controller\HomeController;
use App\Controller\BookController;
use App\Controller\AuthController;
use App\Controller\MessageController;
use App\Controller\AccountController;
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

            case 'book-delete':
                (new BookController())->delete();
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

            case 'account':
               (new AccountController())->account();
                break;

            case 'account-profile':
               (new AccountController())->profile();
                break;

            case 'account-update':
                (new AccountController())->update();
                break;

            case 'messaging':
                (new MessageController())->index();
                break;

            case 'message-start':
                (new MessageController())->startConversation();
                break;

            case 'message-refresh':
                (new MessageController())->refresh();
                break;
 
            default:
                throw new Exception('Page not found.');
        }
    }
}