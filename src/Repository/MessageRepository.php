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

    //Récupère les messages d'une conversation
    public function findByConversationId(
        int $conversationId,
        ?int $lastMessageId = null
    ): array {
        $sql = '
            SELECT
                m.*,
                u.avatar AS sender_avatar
            FROM messages m
            INNER JOIN users u
                ON u.id = m.sender_id
            WHERE m.conversation_id = :conversation_id
        ';

        //Récupère uniquement les nouveaux messages si un dernier identifiant est fourni
        if ($lastMessageId !== null) {
            $sql .= '
                AND m.id > :last_message_id
            ';
        }

        $sql .= '
            ORDER BY m.created_at ASC
        ';

        $query = $this->connection->prepare($sql);

        $params = [
            'conversation_id' => $conversationId
        ];

        if ($lastMessageId !== null) {
            $params['last_message_id'] = $lastMessageId;
        }

        $query->execute($params);

        $messages = [];

        while ($data = $query->fetch()) {
            $messages[] = $this->hydrate($data);
        }

        return $messages;
    }

    //Crée un message dans une conversation
    public function createMessage(
        int $conversationId,
        int $senderId,
        string $content
    ) : Message {
        $query =$this->connection->prepare(
            'INSERT INTO messages (
                conversation_id,
                sender_id,
                content
            ) VALUES (
                :conversation_id,
                :sender_id,
                :content
            )'
        );

        $query->execute([
            'conversation_id' => $conversationId,
            'sender_id' => $senderId,
            'content' => $content
        ]);

        return $this->findById(
            (int) $this->connection->lastInsertId()
        );
    }

    //Compte les messages non lus reçus par un utilisateur
    public function countUnreadMessages(int $userId): int
    {
        $query = $this->connection->prepare(
            'SELECT COUNT(*)
            FROM messages m 
            INNER JOIN conversations c
                On c.id = m.conversation_id
            WHERE m.sender_id != :user_id
            AND m.is_read = 0 
            AND (
                c.user_one_id = :user_id_one
                OR c.user_two_id = :user_id_two
            )'
        );

        $query->execute([
            'user_id' => $userId,
            'user_id_one' => $userId,
            'user_id_two' => $userId
        ]);

        return (int) $query->fetchColumn();
    }

    //Marque comme lus les messages reçus dans une conversation 
    public function markMessagesAsRead(
        int $conversationId,
        int $userId,
        int $lastMessageId
    ): void {
        $query = $this->connection->prepare(
            'UPDATE messages
            SET is_read = 1 
            WHERE conversation_id = :conversation_id
            AND id <= :last_message_id
            AND sender_id != :user_id
            AND is_read = 0'
        );

        $query->execute([
            'conversation_id' => $conversationId,
            'last_message_id' => $lastMessageId,
            'user_id' => $userId
        ]);
    }
}