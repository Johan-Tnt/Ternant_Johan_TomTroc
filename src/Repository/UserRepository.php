<?php

namespace App\Repository;

use App\Entity\User;

class UserRepository extends AbstractRepository
{
    //Nom de la table utilisée
    protected function getTableName(): string
    {
        return 'users';
    }

    //Nom de l'entité utilisée
    protected function getEntityClass(): string
    {
        return User::class;
    }

    //Recherche un utilisateur grâce à son adresse e-mail
    public function findByEmail(string $email): ?User
    {
        $query = $this->connection->prepare(
             'SELECT * FROM ' . $this->getTableName() . ' WHERE email = :email'
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

    //Recherche un utilisateur grâce à son identifiant
    public function findById(int $id): ?User
    {
        $query = $this->connection->prepare(
            'SELECT * FROM ' . $this->getTableName() . ' WHERE id = :id'
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

    //Met à jour les informations d'un utilisateur
    public function update(User $user): bool
    {
        $query = $this->connection->prepare(
            'UPDATE users
            SET
                pseudo = :pseudo,
                email = :email,
                password = :password,
                avatar = :avatar
            WHERE id = :id'
        );

        return $query->execute([
            'id' => $user->getId(),
            'pseudo' => $user->getPseudo(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'avatar' => $user->getAvatar()
        ]);
    }
}