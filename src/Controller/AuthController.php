<?php

namespace App\Controller;

use App\Service\View;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Service\AuthTokenService;
use App\Service\Database;
use App\Service\Config;

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

            //Renouvelle l'identifiant de session après la connexion
            session_regenerate_id(true);

            //Stocke les informations de l'utilisateur dans la session
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['pseudo'] = $user->getPseudo();

            //Gère la connexion persistante 
            $authTokenService = AuthTokenService::getInstance();

            if (isset($_POST['remember'])) {
                //Conserve la connexion pendant 30 jours
                $authTokenService->createRememberToken($user->getId());
            } else {
                //Supprime un éventuel ancien jeton de ce navigateur
                $authTokenService->revokeRememberToken();
            }

            //Redirige vers l'accueil
            header('Location: index.php');
            exit;
        }

        //Récupère le message de confirmation après une réinitialisation
        $message = $_SESSION['password_reset_success'] ?? null;

        //Supprime le message de la session après sa récupération
        unset($_SESSION['password_reset_success']);

        //Affiche le formulaire de connexion
        View::getInstance()->render(
            'login',
            'Connexion',
            [
                'message' => $message
            ]
        );
    }

    //Gère les demandes de réinitialisation du mot de passe
    public function forgotPassword(): void
    {
        //Vérifie si le formulaire a été envoyé
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //Récupère l'adresse e-mail
            $email = trim($_POST['email'] ?? '');

            //Prépare un message générique pour éviter de révéler
            //si une adresse e-mail possède un compte
            $message = 'Si cette adresse correspond à un compte, '
                . 'un e-mail contenant un lien de réinitialisation '
                . 'sera envoyé.';

            //Vérifie que l'adresse e-mail est valide
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $repository = new UserRepository();

                //Recherche l'utilisateur grâce à son adresse e-mail
                $user = $repository->findByEmail($email);

                //Génère un jeton uniquement si l'utilisateur existe
                if ($user !== null) {

                    //Récupère l'adresse e-mail de l'expéditeur
                    $fromAddress = Config::getInstance()->get('MAIL_FROM_ADDRESS');

                    //Récupère l'URL de l'application
                    $appUrl = Config::getInstance()->get('APP_URL');

                    //Vérifie que la configuration est valide
                    if (
                        !is_string($fromAddress)
                        || !filter_var($fromAddress, FILTER_VALIDATE_EMAIL)
                        || !is_string($appUrl)
                        || !filter_var($appUrl, FILTER_VALIDATE_URL)
                        || !in_array(parse_url($appUrl, PHP_URL_SCHEME), ['http', 'https'], true)
                        || empty(parse_url($appUrl, PHP_URL_HOST))
                    ) {
                        error_log('Configuration e-mail ou URL TomTroc invalide.');
                    } else {

                        //Génère un jeton aléatoire sécurisé
                        $token = bin2hex(random_bytes(32));

                        //Calcule l'empreinte du jeton à enregistrer
                        $tokenHash = hash('sha256', $token);

                        //Récupère la connexion à la base de données
                        $connection = Database::getInstance()->getConnection();

                        //Supprime les anciennes demandes de réinitialisation
                        $query = $connection->prepare(
                            'DELETE FROM password_reset_tokens
                            WHERE user_id = :user_id'
                        );

                        $query->execute([
                            'user_id' => $user->getId()
                        ]);

                        //Enregistre le jeton avec une expiration d'une heure
                        $query = $connection->prepare(
                            'INSERT INTO password_reset_tokens (
                                user_id,
                                token_hash,
                                expires_at,
                                created_at
                            ) VALUES (
                                :user_id,
                                :token_hash,
                                DATE_ADD(NOW(), INTERVAL 1 HOUR),
                                NOW()
                            )'
                        );

                        $query->execute([
                            'user_id' => $user->getId(),
                            'token_hash' => $tokenHash
                        ]);

                        //Construit le lien de réinitialisation
                        $resetLink = rtrim($appUrl, '/')
                            . '/index.php?route=reset-password&token='
                            . urlencode($token);

                        //Prépare le sujet de l'e-mail
                        $subject = 'TomTroc - Réinitialisation du mot de passe';

                        //Prépare le contenu de l'e-mail
                        $emailMessage = "Bonjour,\r\n\r\n"
                            . "Tu as demandé la réinitialisation de ton "
                            . "mot de passe TomTroc.\r\n\r\n"
                            . "Pour choisir un nouveau mot de passe, "
                            . "clique sur le lien suivant :\r\n\r\n"
                            . $resetLink . "\r\n\r\n"
                            . "Ce lien expirera dans une heure.\r\n\r\n"
                            . "Si tu n'es pas à l'origine de cette demande, "
                            . "tu peux ignorer cet e-mail.\r\n\r\n"
                            . "L'équipe TomTroc";

                        //Définit l'expéditeur et le format du message
                        $headers = [
                            'From' => 'TomTroc <' . $fromAddress . '>',
                            'Content-Type' => 'text/plain; charset=UTF-8',
                            'X-Mailer' => 'PHP/' . phpversion()
                        ];

                        //Envoie l'e-mail à l'utilisateur
                        $mailSent = mail(
                            $user->getEmail(),
                            $subject,
                            $emailMessage,
                            $headers
                        );

                        //Supprime le jeton si l'envoi échoue
                        if (!$mailSent) {

                            $query = $connection->prepare(
                                'DELETE FROM password_reset_tokens
                                WHERE token_hash = :token_hash'
                            );

                            $query->execute([
                                'token_hash' => $tokenHash
                           ]);

                            //Enregistre l'échec dans le journal PHP
                            error_log(
                                'Échec de l’envoi de l’e-mail '
                                . 'de réinitialisation TomTroc.'
                            );
                        }
                    }
                }
            }

            //Affiche le formulaire avec le message générique
            View::getInstance()->render(
               'forgot-password',
                'Mot de passe oublié',
                [
                    'message' => $message
                ]
            );

             return;
        }

        //Affiche le formulaire lors de la première visite
        View::getInstance()->render(
            'forgot-password',
            'Mot de passe oublié'
        );
    }

    //Gère la réinitialisation du mot de passe
    public function resetPassword(): void
    {
        //Récupère le jeton transmis par le formulaire ou l'URL
        $token = $_SERVER['REQUEST_METHOD'] === 'POST'
            ? ($_POST['token'] ?? '')
            : ($_GET['token'] ?? '');

        //Vérifie que le jeton possède le format attendu
        if (
            !is_string($token)
            || !preg_match('/^[a-f0-9]{64}$/', $token)
        ) {
            View::getInstance()->render(
                'reset-password',
                'Réinitialiser le mot de passe',
                [
                    'error' => 'Ce lien est invalide ou a expiré.',
                    'token' => ''
                ]
            );

            return;
        }

        //Calcule l'empreinte du jeton pour chercher sa correspondance
        $tokenHash = hash('sha256', $token);

        //Récupère la connexion à la base de données 
        $connection = Database::getInstance()->getConnection();

        //Récupère un jeton valide et non expiré
        $query = $connection->prepare(
            'SELECT user_id
            FROM password_reset_tokens
            WHERE token_hash = :token_hash
            AND expires_at > NOW()
            LIMIT 1'
        );

        $query->execute([
            'token_hash' => $tokenHash
        ]);

        $resetToken = $query->fetch();

        //Vérifie que le jeton existe et n'a pas expiré
        if ($resetToken === false) {
            View::getInstance()->render(
                'reset-password',
                'Réinitialiser le mot de passe',
                [
                    'error' => 'Ce lien est invalide ou a expiré.',
                    'token' =>''
                ]
            );

            return;
        }

        //Vérifie si le formulaire a été envoyé
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //Récupère les mots de passe saisis
            $password = $_POST['password'] ?? '';
            $passwordConfirmation = $_POST['password_confirmation'] ?? '';

            //Vérifie que les deux champs sont des chaînes de caractères
            if (
                !is_string($password)
                || !is_string($passwordConfirmation)
            ) {
                View::getInstance()->render(
                    'reset-password',
                    'Réinitialiser le mot de passe',
                    [
                        'error' => 'Les mots de passe renseignés sont invalides.',
                        'token' => $token
                    ]
                );

                return; 
            }

            //Vérifie que les deux champs sont renseignés
            if ($password === '' || $passwordConfirmation === '') {
                View::getInstance()->render(
                    'reset-password',
                    'Réinitialiser le mot de passe',
                    [
                        'error' => 'Veuillez remplir tous les champs.',
                        'token' => $token
                    ]
                );

                return;
            }

            //Vérifie la longueur minimale du mot de passe
            if (strlen($password) < 8) {
                View::getInstance()->render(
                    'reset-password',
                    'Réinitialiser le mot de passe',
                    [
                        'error' => 'Le mot de passe doit contenir au moins 8 caractères.',
                        'token' => $token
                    ]
                );

                return;
            }

            //Vérifie que les mots de passe correspondent
            if ($password !== $passwordConfirmation) {
                View::getInstance()->render(
                    'reset-password',
                    'Réinitialiser le mot de passe',
                    [
                        'error' => 'Les mots de passe ne correspondent pas.',
                        'token' => $token
                    ]
                );

                return;
            }

            //Hache le nouveau mot de passe 
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            try {
                //Démarre une transaction pour sécuriser les modifications 
                $connection->beginTransaction();

                //Met à jour le mot de passe de l'utilisateur
                $query = $connection->prepare(
                    'UPDATE users
                    SET password = :password
                    WHERE id = :user_id'         
                );

                $query->execute([
                    'password' => $hashedPassword,
                    'user_id' => $resetToken['user_id']
                ]);

                //Supprime le jeton de réinitialisation utilisé
                $query = $connection->prepare(
                    'DELETE FROM password_reset_tokens
                    WHERE token_hash = :token_hash'
                );

                $query->execute([
                    'token_hash' => $tokenHash
                ]);

                //Invalide toutes les connexions persistantes de l'utilisateur
                $query = $connection->prepare(
                    'DELETE FROM remember_tokens
                    WHERE user_id = :user_id'
                );

                $query->execute([
                    'user_id' => $resetToken['user_id']
                ]);

                //Valide les modifications en base de données
                $connection->commit();
            } catch (\Throwable $exception) {

                //Annule les modifications en cas d'erreur
                if ($connection->inTransaction()) {
                    $connection->rollBack();
                }

                throw $exception;
            }

            //Prépare un message de confirmation pour la page de connexion
            $_SESSION['password_reset_success'] = 
                'Votre mot de passe a été modifié. Vous pouvez vous connecter.';

            //Redirige vers la page de connexion
            header('Location: index.php?route=login');
            exit;
        }

        //Affiche le formulaire avec le jeton valide
        View::getInstance()->render(
            'reset-password',
            'Réinitialiser le mot de passe',
            [
                'token' => $token
            ]
        );
    }

    //Déconnecte l'utilisateur
    public function logout(): void
    {
        //Révoque le jeton de connexion persistante
        AuthTokenService::getInstance()->revokeRememberToken();
        
        //Détruit les données de session
        session_unset();

        //Détruit la session
        session_destroy();

        //Redirige vers l'accueil
        header('Location: index.php');
        exit;
    }
}