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

    //Récupère tous les livres
    public function findAll(): array
    {
        $query = $this->connection->query(
            'SELECT * FROM books ORDER BY created_at DESC'
        );

        $books = [];

        while ($data = $query->fetch()) {
            $books[] = $this->hydrate($data);
        }

        return $books;
    }

    //Récupère un livre grâce à son identifiant
    public function findById(int $id): ?Book
    {
        $query = $this->connection->prepare(
            'SELECT * FROM books WHERE id = :id'
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

    //Transforme les données SQL en objet Book
    protected function hydrate(array $data): Book
    {
        $book = new Book(
            (int) $data['user_id'],
            $data['title'],
            $data['author'],
            $data['description'],
            $data['picture'],
            $data['availability']
        );

        //Ajoute les données générées par la base
        $book->setId((int) $data['id']);
        $book->setCreatedAt($data['created_at']);
        $book->setUpdatedAt($data['updated_at']);

        return $book;
    }
}