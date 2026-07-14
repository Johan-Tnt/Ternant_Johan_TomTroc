<?php

namespace App\Controller;

use App\Service\View;

class BookController
{
    public function index(): void
    {
        View::getInstance()->render('books', 'Nos livres');
    }
}