<?php

namespace App\Controller;

use App\Service\View;
use App\Repository\UserRepository;
use App\Entity\User;

class UserController
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
}