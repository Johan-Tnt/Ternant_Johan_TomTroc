<?php

namespace App\Repository;

use App\Entity\Message;
use App\Service\Database;
use PDO;

class MessageRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    //Récupère un message grâce à son identifiant
    public function findById(int $id): ?Message
    {
        $query = $this->connection->prepare(
            'SELECT * FROM messages WHERE id = :id'
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

    //Récupère tous les messages d'une conversation
    public function findByConversationId(int $conversationId): array
    {
        $query = $this->connection->prepare(
            'SELECT * FROM messages
            WHERE conversation_id = :conversation_id
            ORDER BY created_at ASC'
        );

        $query->execute([
            'conversation_id' => $conversationId
        ]);

        $messages = [];

        while ($data = $query->fetch()) {
            $messages[] = $this->hydrate($data);
        }

        return $messages;
    }

    //Transforme les données SQL en objet Message
    private function hydrate(array $data): Message
    {
        $message = new Message(
            (int) $data['conversation_id'],
            (int) $data['sender_id'],
            $data['content']
        );

        //Ajoute les données générées par la base
        $message->setId((int) $data['id']);
        $message->setCreatedAt($data['created_at']);

        return $message;
    }
}