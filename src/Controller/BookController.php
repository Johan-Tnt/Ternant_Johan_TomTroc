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

    //Affiche le formulaire d'ajout d'un livre
    public function create(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit;
        }

        $bookRepository = new BookRepository();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $title = trim($_POST['title'] ?? '');
            $author = trim($_POST['author'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $availability = $_POST['availability'] ?? 'available';

            $userId = (int) $_SESSION['user_id'];

            if ($title === '' || $author === '') {

                View::getInstance()->render(
                    'book-form',
                    'Ajouter un livre',
                    [
                        'book' => $_POST,
                        'formTitle' => 'Ajouter un livre',
                        'formAction' => 'book-add',
                        'error' => 'Le titre et l’auteur sont obligatoires.'
                    ]
                );

                return;
            }

            $picture = $this->uploadPicture();

            $bookRepository->create(
                $userId,
                $title,
                $author,
                $description !== '' ? $description : null,
                $picture,
                $availability
            );

            header('Location: index.php?route=books');
            exit;
        }

        View::getInstance()->render(
            'book-form',
            'Ajouter un livre',
            [
                'book' => null,
                'formTitle' => 'Ajouter un livre',
                'formAction' => 'book-add'
            ]
        );
    }


    //Affiche le formulaire de modification d'un livre
    public function edit(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit;
        }

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

        $userId = (int) $_SESSION['user_id'];

        //Vérifie que l'utilisateur connecté est propriétaire du livre
        if ((int) $book['user_id'] !== $userId) {
            http_response_code(403);

            View::getInstance()->render(
                '403',
                'Accès refusé'
            );

            return;
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $title = trim($_POST['title'] ?? '');
            $author = trim($_POST['author'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $availability = $_POST['availability'] ?? 'available';

            if ($title === '' || $author === '') {

                View::getInstance()->render(
                    'book-form',
                    'Modifier les informations',
                    [
                        'book' => array_merge($book, $_POST),
                        'formTitle' => 'Modifier les informations',
                        'formAction' => 'book-edit&id=' . $id,
                        'error' => 'Le titre et l’auteur sont obligatoires.'
                    ]
                );

                return;
            }

            //Charge une nouvelle image
            $picture = $this->uploadPicture();

            //Si aucune nouvelle image n'est sélectionnée, on conserve l'ancienne image
            if ($picture === null) {
                $picture = $book['picture'];
            }

            $bookRepository->update(
                $id,
                $userId,
                $title,
                $author,
                $description !== '' ? $description : null,
                $picture,
                $availability
            );

            header(
                'Location: index.php?route=book-details&id=' . $id
            );

            exit;
        }


        View::getInstance()->render(
            'book-form',
            'Modifier les informations',
            [
                'book' => $book,
                'formTitle' => 'Modifier les informations',
                'formAction' => 'book-edit&id=' . $id
            ]
        );
    }

    //Enregistre une image dans le dossier des livres
    private function uploadPicture(): ?string
    {
        if (
            !isset($_FILES['picture'])
            || $_FILES['picture']['error'] === UPLOAD_ERR_NO_FILE
        ) {
            return null;
        }

        if ($_FILES['picture']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];

        $filesType = mime_content_type(
            $_FILES['picture']['tmp_name']
        );

        if (!isset($allowedTypes[$filesType])) {
            return null;
        }

        $fileName = uniqid() . '.' . $allowedTypes[$filesType];

        $uploadDirectory =
            __DIR__ . '/../../public/assets/images/pictures-books/';

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        $uploadPath = $uploadDirectory . $fileName;

        
        if (!move_uploaded_file(
            $_FILES['picture']['tmp_name'],
            $uploadPath
        )) {
            return null;
        }

        return $fileName;
    }
}