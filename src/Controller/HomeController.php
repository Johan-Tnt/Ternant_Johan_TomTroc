<?php

namespace App\Controller;

use App\Service\View;
use App\Repository\BookRepository;
class HomeController
{
    public function index(): void
    {
        $bookRepository = new BookRepository();

        $latestBooks = $bookRepository->findLatestWithUsers();

        View::getInstance()->render(
            'home',
            'Accueil',
            [
                'latestBooks' => $latestBooks
            ]
        );
    }
}