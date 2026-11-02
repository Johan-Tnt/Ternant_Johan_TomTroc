<?php

namespace App\Service;

use PDO;

class AuthTokenService
{
    //Durée de validité du jeton : 30 jours
    private const REMEMBER_DURATION = 2592000;

    //Nom du cookie de connexion persistante
    private const REMEMBER_COOKIE = 'tomtroc_remember';

    //Connexion à la base de données
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    //Crée un jeton de connexion persistante
    public function createRememberToken(int $userId): void
    {
        //Génère un jeton aléatoire
        $token = bin2hex(random_bytes(32));

        //Stocke uniquement son empreinte en base de données
        $tokenHash = hash('sha256', $token);

        //Définit la date d'expiration
        $expiresAt = date(
            'Y-m-d H:i:s',
            time() + self::REMEMBER_DURATION
        );

        //Supprime les anciens jetons de cet utilisateur
        $statement = $this->connection->prepare(
            'DELETE FROM remember_tokens WHERE user_id = :user_id'
        );

        $statement->execute([
            'user_id' => $userId,
        ]);

        //Enregistre le nouveau jeton
        $statement = $this->connection->prepare(
            'INSERT INTO remember_tokens (user_id, token_hash, expires_at)
             VALUES (:user_id, :token_hash, :expires_at)'
        );

        $statement->execute([
            'user_id' => $userId,
            'token_hash' => $tokenHash,
            'expires_at' => $expiresAt,
        ]);

        //Configure le cookie de connexion persistante
        $this->setRememberCookie(
            $token,
            time() + self::REMEMBER_DURATION
        );
    }

    //Restaure la connexion si la session a expiré
    public function restoreRememberedLogin(): void
    {
        //Une session déjà authentifiée n'a pas besoin d'être restaurée
        if (!empty($_SESSION['user_id'])) {
            return;
        }

        //Vérifie la présence du cookie
        $token = $_COOKIE[self::REMEMBER_COOKIE] ?? '';

        //Vérifie le format du jeton
        if (
            !is_string($token)
            || !preg_match('/^[a-f0-9]{64}$/', $token)
        ) {
            if (isset($_COOKIE[self::REMEMBER_COOKIE])) {
                $this->clearRememberCookie();
            }

            return;
        }

        //Calcule l'empreinte du jeton reçu
        $tokenHash = hash('sha256', $token);

        //Recherche un jeton valide et non expiré
        $statement = $this->connection->prepare(
            'SELECT users.id, users.pseudo
             FROM remember_tokens
             INNER JOIN users ON users.id = remember_tokens.user_id
             WHERE remember_tokens.token_hash = :token_hash
             AND remember_tokens.expires_at > NOW()
             LIMIT 1'
        );

        $statement->execute([
            'token_hash' => $tokenHash,
        ]);

        $user = $statement->fetch();

        //Aucun jeton valide : supprime le cookie
        if (!$user) {
            $this->clearRememberCookie();
            return;
        }

        //Renouvelle l'identifiant de session
        session_regenerate_id(true);

        //Restaure les informations de session
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['pseudo'] = $user['pseudo'];

        //Renouvelle le jeton pour limiter les risques de réutilisation
        $this->createRememberToken((int) $user['id']);
    }

    //Révoque le jeton de connexion persistante
    public function revokeRememberToken(): void
    {
        $token = $_COOKIE[self::REMEMBER_COOKIE] ?? '';

        if (
            is_string($token)
            && preg_match('/^[a-f0-9]{64}$/', $token)
        ) {
            $tokenHash = hash('sha256', $token);

            $statement = $this->connection->prepare(
                'DELETE FROM remember_tokens
                 WHERE token_hash = :token_hash'
            );

            $statement->execute([
                'token_hash' => $tokenHash,
            ]);
        }

        //Supprime le cookie du navigateur
        $this->clearRememberCookie();
    }

    //Configure le cookie de connexion persistante
    private function setRememberCookie(string $token, int $expiresAt): void
    {
        //Détermine le chemin de l'application
        $path = str_replace(
            '\\',
            '/',
            dirname($_SERVER['SCRIPT_NAME'] ?? '/')
        );

        if ($path === '.' || $path === '/') {
            $path = '/';
        } else {
            $path = rtrim($path, '/') . '/';
        }

        //Le cookie est sécurisé si le site utilise HTTPS
        $isSecure = isset($_SERVER['HTTPS'])
            && $_SERVER['HTTPS'] !== ''
            && $_SERVER['HTTPS'] !== 'off';

        setcookie(self::REMEMBER_COOKIE, $token, [
            'expires' => $expiresAt,
            'path' => $path,
            'secure' => $isSecure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    //Supprime le cookie de connexion persistante
    private function clearRememberCookie(): void
    {
        //Détermine le chemin de l'application
        $path = str_replace(
            '\\',
            '/',
            dirname($_SERVER['SCRIPT_NAME'] ?? '/')
        );

        if ($path === '.' || $path === '/') {
            $path = '/';
        } else {
            $path = rtrim($path, '/') . '/';
        }

        //Le cookie est sécurisé si le site utilise HTTPS
        $isSecure = isset($_SERVER['HTTPS'])
            && $_SERVER['HTTPS'] !== ''
            && $_SERVER['HTTPS'] !== 'off';

        setcookie(self::REMEMBER_COOKIE, '', [
            'expires' => time() - 3600,
            'path' => $path,
            'secure' => $isSecure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        unset($_COOKIE[self::REMEMBER_COOKIE]);
    }
}