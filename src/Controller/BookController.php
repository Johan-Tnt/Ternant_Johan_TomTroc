<?php

namespace App\Controller;

use App\Service\View;
use App\Repository\BookRepository;

class BookController
{
    public function index(): void
    {
        $bookRepository = new BookRepository();

        $search = trim($_GET['search'] ?? '');

        if ($search !== '') {
            $books = $bookRepository->searchWithUsers($search);
        } else {
            $books = $bookRepository->findAllWithUsers();
        }

        View::getInstance()->render(
            'books',
            'Nos livres',
            [
                'books' => $books
            ]
        );
    }

    public function show(): void
    {
        $bookRepository = new BookRepository();

        $id = (int) ($_GET['id'] ?? 0);

        $book = $bookRepository->findOneWithUser($id);

        if ($book === null) {
            http_response_code(404);
            View::getInstance()->render(
                '404',
                'Livre introuvable'
            );
            return;
        }

        View::getInstance()->render(
            'book-details',
            'Détails du livre',
            [
                'book' => $book
            ]
        );
    }
}