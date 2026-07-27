<?php

namespace App\Repository;

use App\Entity\Conversation;
use App\Service\Database;
use PDO;

class ConversationRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    //Récupère une conversation grâce à son identifiant
    public function findById(int $id): ?Conversation
    {
        $query = $this->connection->prepare(
            'SELECT * FROM conversations WHERE id = :id'
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

    //Récupère toutes les conversations d'un utilisateur
    public function findByUserId(int $userId): array
    {
        $query = $this->connection->prepare(
            'SELECT * FROM conversations
            WHERE user_one_id = :user_id
            OR user_two_id = :user_id
            ORDER BY created_at DESC'
        );

        $query->execute([
            'user_id' => $userId
        ]);

        $conversations = [];

        while ($data = $query->fetch()) {
            $conversations[] = $this->hydrate($data);
        }

        return $conversations;
    }

    //Recherche une conversation entre deux utilisateurs
    public function findBetweenUsers(
        int $userOneId,
        int $userTwoId
    ): ?Conversation {
        $query = $this->connection->prepare(
            'SELECT * FROM conversations
            WHERE (user_one_id = :user_one_id AND user_two_id = :user_two_id)
            OR (user_one_id = :user_two_id AND user_two_id = :user_one_id)
            LIMIT 1'
        );

        $query->execute([
            'user_one_id' => $userOneId,
            'user_two_id' => $userTwoId
        ]);

        $data = $query->fetch();

        if ($data === false) {
            return null;
        }

        return $this->hydrate($data);
    }

    //Transforme les données SQL en objet Conversation
    private function hydrate(array $data): Conversation
    {
       $conversation = new Conversation(
            (int) $data['user_one_id'],
            (int) $data['user_two_id']
        );

        //Ajoute les données générées par la base
        $conversation->setId((int) $data['id']);
        $conversation->setCreatedAt($data['created_at']);

        return $conversation;
    }
}