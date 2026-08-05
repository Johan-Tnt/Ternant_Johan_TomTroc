<?php

namespace App\Repository;

use App\Entity\Message;
class MessageRepository  extends AbstractRepository
{
    //Nom de la table utilisée
    protected function getTableName(): string
    {
        return 'messages';
    }

    //Nom de l'entité utilisée
    protected function getEntityClass(): string
    {
        return Message::class;
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
}