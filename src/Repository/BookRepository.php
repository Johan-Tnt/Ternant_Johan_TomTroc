<?php

namespace App\Repository;

use App\Entity\Book;
use PDO;

class BookRepository extends AbstractRepository
{
    private const BOOK_WITH_USER_QUERY = 
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
    ON books.user_id = users.id';

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
            self::BOOK_WITH_USER_QUERY
            . ' ORDER BY books.created_at DESC'
        );

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    //Rechercher des livres avec leur vendeur
    public function searchWithUsers(string $search): array
    {
        $query = $this->connection->prepare(
            self::BOOK_WITH_USER_QUERY
            . ' WHERE books.title LIKE :search
                OR books.author LIKE :search
                ORDER BY books.created_at DESC'
    );

    $query->execute([
        'search' => '%' . $search . '%'
    ]);

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

    //Récupère les 4 derniers livres avec leur propriétaire
    public function findLatestWithUsers(int $limit = 4): array
    {
        $limit = max(1, $limit);

        $query = $this->connection->query(
            self::BOOK_WITH_USER_QUERY
            . ' ORDER BY books.created_at DESC
            LIMIT ' . $limit
        );

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    //Crée un nouveau livre
    public function create(
        int $userId,
        string $title,
        string $author,
        ?string $description,
        ?string $picture,
        string $availability
    ): bool {
        $query = $this->connection->prepare(
            'INSERT INTO books
            (user_id, title, author, description, picture, availability)
            VALUES
            (:user_id, :title, :author, :description, :picture, :availability)'
        );

        return $query->execute([
            'user_id' => $userId,
            'title' => $title,
            'author' => $author,
            'description' => $description,
            'picture' => $picture,
            'availability' => $availability
        ]);
    }


    //Modifie un livre
    public function update(
        int $id,
        int $userId,
        string $title,
        string $author,
        ?string $description,
        ?string $picture,
        string $availability
    ): bool {
        $query = $this->connection->prepare(
            'UPDATE books
            SET
                title = :title,
                author = :author,
                description = :description,
                picture = :picture,
                availability = :availability
            WHERE id = :id
            AND user_id = :user_id'
        );

        return $query->execute([
            'id' => $id,
            'user_id' => $userId,
            'title' => $title,
            'author' => $author,
            'description' => $description,
            'picture' => $picture,
            'availability' => $availability
        ]);
    }
}