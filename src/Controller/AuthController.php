<?php

namespace App\Controller;

use App\Service\View;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Repository\BookRepository;

class AuthController
{
    //Affiche la page d'inscription du site
    public function register(): void
    {
        //Vérifie que le formulaire a été envoyé
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //Récupère les données du formulaire
            $pseudo = trim($_POST['pseudo']);
            $email = trim($_POST['email']);

            //Vérifie que tous les champs sont remplis
            if (
                empty($pseudo)
                || empty($email)
                || empty($_POST['password'])
            ) {
                View::getInstance()->render(
                    'register',
                    'Inscription'
                );

                return;
            }

            $repository = new UserRepository();

            //Vérifie que l'adresse e-mail n'existe pas déjà
            if ($repository->findByEmail($email) !== null) {

                View::getInstance()->render(
                    'register',
                    'Inscription',
                    [
                        'error' => 'Cette adresse e-mail existe déjà.'
                    ]
                );

                return;
            }

            //Sécurise le mot de passe
            $password = password_hash(
                $_POST['password'],
                PASSWORD_DEFAULT
            );

            //Crée un nouvel utilisateur
            $user = new User(
                $pseudo,
                $email,
                $password
            );

            //Enregistre l'utilisateur dans la base de données
            $repository->create($user);

            //Redirige vers la page de connexion
            header('Location: index.php?route=login');
            exit;
        }

        //Affiche le formulaire d'inscription
        View::getInstance()->render(
            'register',
            'Inscription'
        );
    }

    //Affiche la page de connexion
    public function login(): void
    {
        //Vérifie que le formulaire a été envoyé
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //Récupère les données du formulaire
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            //Vérifie que tous les champs sont remplis
            if (
                empty($email)
                || empty($password)
            ) {
                View::getInstance()->render(
                    'login',
                    'Connexion',
                    [
                        'error' => 'Veuillez remplir tous les champs.'
                    ]
                );

                return;
            }

            $repository = new UserRepository();

            //Recherche l'utilisateur grâce à son e-mail
            $user = $repository->findByEmail($email);

            //Vérifie que l'utilisateur existe et que le mot de passe est correct
            if (
                $user === null
                || !password_verify($password, $user->getPassword())
            ) {
                View::getInstance()->render(
                    'login',
                    'Connexion',
                    [
                        'error' => 'Adresse e-mail ou mot de passe incorrect.'
                    ]
                );

                return;
            }

            //Stocke les informations de l'utilisateur dans la session
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['pseudo'] = $user->getPseudo();

            //Redirige vers l'accueil
            header('Location: index.php');
            exit;
        }

        //Affiche le formulaire de connexion
        View::getInstance()->render(
            'login',
            'Connexion'
        );
    }

    //Déconnecte l'utilisateur
    public function logout(): void
    {
        //Détruit les données de session
        session_unset();

        //Détruit la session
        session_destroy();

        //Redirige vers l'accueil
        header('Location: index.php');
        exit;
    }

    //Affiche la page du compte utilisateur
    public function account(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit;
        }

        $userRepository = new UserRepository;
        $bookRepository = new BookRepository;

        $userId = (int) $_SESSION['user_id'];

        $user = $userRepository->findById($userId);

        if ($user == null) {
            session_unset();
            session_destroy();

            header('Location: index.php?route=login');
            exit;
        }

        $books = $bookRepository->findByUserId($userId);

        View::getInstance()->render(
            'account',
            'Mon compte',
            [
                'user' => $user,
                'books' => $books,
                'bookCount' => count($books)
            ]
        );
    }

    //Met à jour les informations du compte
    public function update(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=account');
            exit;
        }

        $userRepository = new UserRepository();
        $bookRepository = new BookRepository();

        $userId = (int) $_SESSION['user_id'];

        $user = $userRepository->findById($userId);

        if ($user === null) {
            session_unset();
            session_destroy();

            header('Location: index.php?route=login');
            exit;
        }

        $books = $bookRepository->findByUserId($userId);

        //Gestion du nouvel avatar
        if (
            isset($_FILES['avatar'])
            && $_FILES['avatar']['error'] === UPLOAD_ERR_OK
        ) {
            $allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            $fileType = mime_content_type(
                $_FILES['avatar']['tmp_name']
             );

            if (!in_array($fileType, $allowedTypes, true)) {
                View::getInstance()->render(
                    'account',
                    'Mon compte',
                    [
                        'user' => $user,
                        'books' => $books,
                        'bookCount' => count($books),
                        'error' => 'Le format de l’image n’est pas valide.'
                    ]
                );

                return;
            }

            $extension = match ($fileType) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp'
            };

            $avatarName = uniqid('avatar_', true) . '.' . $extension;

            $avatarPath = __DIR__
                . '/../../public/assets/images/avatars/'
                . $avatarName;

            if (
                !move_uploaded_file(
                    $_FILES['avatar']['tmp_name'],
                    $avatarPath
                )
            ) {
                View::getInstance()->render(
                    'account',
                    'Mon compte',
                    [
                        'user' => $user,
                        'books' => $books,
                        'bookCount' => count($books),
                        'error' => 'Impossible d’enregistrer l’image.'
                    ]
                );

                return;
            }

            //Supprime l'ancien avatar
            if (!empty($user->getAvatar()) 
                && $user->getAvatar() !== 'default-avatar.jpg'
            ) {
                $oldAvatarPath = __DIR__
                    . '/../../public/assets/images/avatars/'
                    . $user->getAvatar();

                if (
                    file_exists($oldAvatarPath)
                    && is_file($oldAvatarPath)
                ) {
                    unlink($oldAvatarPath);
                }
            }

            $user->setAvatar($avatarName);

            $userRepository->update($user);

            header('Location: index.php?route=account');
            exit;
        }

        //Modification des informations personnelles
        $pseudo = trim($_POST['pseudo'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($pseudo === '' || $email === '') {
            View::getInstance()->render(
                'account',
                'Mon compte',
                [
                    'user' => $user,
                    'books' => $books,
                    'bookCount' => count($books),
                    'error' => 'Le pseudo et l’adresse e-mail sont obligatoires.'
                ]
            );

            return;
        }

        //Vérifie que l'adresse e-mail n'est pas déjà utilisée
        $existingUser = $userRepository->findByEmail($email);

        if (
            $existingUser !== null
            && $existingUser->getId() !== $userId
        ) {
            View::getInstance()->render(
                'account',
                'Mon compte',
                [
                    'user' => $user,
                    'books' => $books,
                    'bookCount' => count($books),
                    'error' => 'Cette adresse e-mail est déjà utilisée.'
                ]
                );

            return;
        }

        //Modifie le mot de passe uniquement s'il a été renseigné
        if ($password !== '') {
            $user->setPassword(
                password_hash(
                    $password,
                    PASSWORD_DEFAULT
                )
            );
        }

        $user->setPseudo($pseudo);
        $user->setEmail($email);

        $userRepository->update($user);

        $_SESSION['pseudo'] = $user->getPseudo();

        header('Location: index.php?route=account');
        exit;
    }
}