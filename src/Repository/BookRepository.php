<?php

namespace App\Repository;

use App\Entity\Book;

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
}