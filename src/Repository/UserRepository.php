<?php

namespace App\Repository;

use App\Entity\User;
use App\Service\Database;
use PDO;

class UserRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    //Récupère tous les utilisateurs
    public function findAll(): array
    {
        $query = $this->connection->query(
            'SELECT * FROM users ORDER BY created_at DESC'
        );

        $users = [];

        while ($data = $query->fetch()) {
            $users[] = $this->hydrate($data);
        }

        return $users;
    }

    //Récupère un utilisateur grâce à son identifiant
    public function findById(int $id): ?User
    {
        $query = $this->connection->prepare(
            'SELECT * FROM users WHERE id = :id'
        );

        $query->execute([
            'id' => $id
        ]);

        $data = $query->fetch();

        if ($data === false) {
            return null;
        }

        return $this->hydrate($data);
    }

    //Recherche un utilisateur grâce à son adresse e-mail
    public function findByEmail(string $email): ?User
    {
        $query = $this->connection->prepare(
            'SELECT * FROM users WHERE email = :email'
        );

        $query->execute([
            'email' => $email
        ]);

        $data = $query->fetch();

        if ($data === false) {
            return null;
        }

        return $this->hydrate($data);
    }

    //Enregistre un nouvel utilisateur
    public function create(User $user): void
    {
        $query = $this->connection->prepare(
            'INSERT INTO users (
                pseudo, 
                email, 
                password,
                avatar 
                ) VALUES ( 
                :pseudo,
                :email,
                :password,
                :avatar 
            )'
        );

        $query->execute([
        'pseudo' => $user->getPseudo(),
        'email' => $user->getEmail(),
        'password' => $user->getPassword(),
        'avatar' => $user->getAvatar()
        ]);
    }

    //Transforme les données SQL en objet User
    private function hydrate(array $data): User
    {
        $user = new User(
            $data['pseudo'],
            $data['email'],
            $data['password'],
            $data['avatar']
        );

        //Ajoute les données générées par la base
        $user->setId((int) $data ['id']);
        $user->setCreatedAt($data['created_at']);

        return $user;
    }
}