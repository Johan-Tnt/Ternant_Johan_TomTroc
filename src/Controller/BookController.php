<?php

namespace App\Controller;

use App\Service\View;
use App\Repository\BookRepository;
use App\Service\ImageService;

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

            $title = strip_tags(
                trim($_POST['title'] ?? '')
            );

            $author = strip_tags(
                trim($_POST['author'] ?? '')
            );

            $description = strip_tags(
                trim($_POST['description'] ?? '')
            );
            
            $availability = $_POST['availability'] ?? 'available';

            if (
                !in_array(
                    $availability,
                    ['available', 'unavailable'],
                    true
                )
            ) { 
                $availability ='available';
            }

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

            $imageService = new ImageService();

            $picture = $imageService->upload();

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
                'book' => [
                    'picture' => 'default-book.jpg'
                ],
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

            $title = strip_tags(
                trim($_POST['title'] ?? '')
            );

            $author = strip_tags(
                trim($_POST['author'] ?? '')
            );

            $description = strip_tags(
                trim($_POST['description'] ?? '')
            );
            
            $availability = $_POST['availability'] ?? 'available';

            if (
                !in_array(
                    $availability,
                    ['available', 'unavailable'],
                    true
                )
            ) { 
                $availability ='available';
            }

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

            $imageService = new ImageService();

            //Charge une nouvelle image
            $picture = $imageService->upload();

            //Si une nouvelle image a été ajoutée
            if ($picture !== null) {

                //Supprime l'ancienne image
                $imageService->delete(
                    $book['picture']
                );
            } else {

                //Consevre l'ancienne image
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

    //Supprime un livre 
    public function delete(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=account');
            exit;
        }

        $bookRepository = new BookRepository();
        $imageService = new ImageService();

        $userId = (int) $_SESSION['user_id'];
        $id = (int) ($_POST['id'] ?? 0);

        $book = $bookRepository->findOneWithUser($id);

        if ($book === null) {
            http_response_code(404);

            View::getInstance()->render(
                '404',
                'Livre introuvable'
            );

            return;
        }

        //Verifie que l'utlisateur connecté est le propriétaire du livre
        if ((int) $book['user_id'] !== $userId) {
            http_response_code(403);

            View::getInstance()->render(
                '403',
                'Accès refusé'
            );

            return;
        }

        //Supprime l'image  du livre
        if (!empty($book['picture'])) {
                $imageService->delete(
                    $book['picture']
                );
        }

        //Supprime le livre de la base de données
        $bookRepository->delete(
            $id,
            $userId
        );

        //Retourne sur la page du compte
        header('Location: index.php?route=account');
        exit;
    }
}