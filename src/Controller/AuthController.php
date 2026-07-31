<?php

namespace App\Controller;

use App\Service\View;
use App\Repository\UserRepository;

class AuthController
{
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
}