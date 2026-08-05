<?php

namespace App\Repository;

use App\Entity\Message;
class MessageRepository  extends AbstractRepository
{
    //Nom de la table utilisée
    protected function getTableName(): string
    {
        return 'users';
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
    protected function hydrate(array $data): Message
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