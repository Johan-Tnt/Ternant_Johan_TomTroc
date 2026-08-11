<?php

namespace App\Repository;

use App\Entity\Book;
use PDO;

class BookRepository extends AbstractRepository
{
    //Nom de la table utilisée
    protected function getTableName(): string
    {
        return 'books';
    }

    //Nom de l'entité utilisée
    protected function getEntityClass(): string
    {
        return Book::class;
    }

    //Récupère les livres appartenant à un utilisateur
    public function findByUserId(int $userId): array
    {
        $query = $this->connection->prepare(
            'SELECT * FROM books WHERE user_id = :user_id ORDER BY created_at DESC'
        );

        $query->execute([
            'user_id' => $userId
        ]);

        $books = [];

        while ($data = $query->fetch()) {
            $books[] = $this->hydrate($data);
        }

        return $books;
    }

    //Récupère tous les livres avec leur vendeur
    public function findAllWithUsers(): array
    {
        $query = $this->connection->query(
           'SELECT
            books.id,
            books.title,
            books.author,
            books.description,
            books.picture,
            books.availability,
            users.pseudo
        FROM books
        INNER JOIN users
        ON books.user_id = users.id
        ORDER BY books.created_at DESC'
    );

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    //Récupère un livre avec son propriétaire
    public function findOneWithUser(int $id): ?array
    {
        $query = $this->connection->prepare(
            'SELECT
                books.id,
                books.user_id,
                books.title,
                books.author,
                books.description,
                books.picture,
                books.availability,
                users.pseudo,
                users.avatar
            FROM books
            INNER JOIN users
            ON books.user_id = users.id
            WHERE books.id = :id'
        );

        $query->execute([
            'id' => $id
        ]);

        $book = $query->fetch(PDO::FETCH_ASSOC);

        return $book ?: null;
    }

    // Récupère les 4 derniers livres avec leur propriétaire
    public function findLatestWithUsers(int $limit = 4): array
    {
        $query = $this->connection->query(
            'SELECT
                books.id,
                books.title,
                books.author,
                books.description,
                books.picture,
                books.availability,
                users.pseudo
            FROM books
            INNER JOIN users
            ON books.user_id = users.id
            ORDER BY books.created_at DESC
            LIMIT ' . $limit
        );

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}