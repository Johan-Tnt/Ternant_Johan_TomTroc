<?php

namespace App\Repository;

use App\Entity\Conversation;

class ConversationRepository extends AbstractRepository
{
    //Nom de la table utilisée
    protected function getTableName(): string
    {
        return 'conversations';
    }

    //Nom de l'entité utilisée
    protected function getEntityClass(): string
    {
        return Conversation::class;
    }

    //Récupère toutes les conversations d'un utilisateur
    public function findByUserId(int $userId): array
    {
        $query = $this->connection->prepare(
            'SELECT * FROM conversations
            WHERE user_one_id = :user_one_id
            OR user_two_id = :user_two_id
            ORDER BY created_at DESC'
        );

        $query->execute([
            'user_one_id' => $userId,
            'user_two_id' => $userId
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

    //Crée une conversation entre deux utilisateurs
    public function createConversation(
        int $userOneId,
        int $userTwoId
    ): Conversation {
        $query = $this->connection->prepare(
            'INSERT INTO conversations (
                user_one_id,
                user_two_id
            ) VALUES (
                :user_one_id,
                :user_two_id
            )'
        );

        $query->execute([
            'user_one_id' => $userOneId,
            'user_two_id' => $userTwoId
        ]);

        $conversation = $this->findById(
            (int) $this->connection->lastInsertId()
        );

        if (!$conversation instanceof Conversation) {
            throw new \RuntimeException(
                'La conversation n\'a pas pu être créée.'
            );
        }

        return $conversation;
    }

    //Récupère les conversations avec l'autre utilisateur et le dernier message
    public function findByUserIdWithDetails(int $userId): array
    {
        $query = $this->connection->prepare(
            'SELECT
                c.id,
                c.user_one_id,
                c.user_two_id,
                CASE
                    WHEN c.user_one_id = :user_id_case THEN user_two.id
                    ELSE user_one.id
                END AS other_user_id,
                CASE
                    WHEN c.user_one_id = :user_id_case THEN user_two.pseudo
                    ELSE user_one.pseudo
                END AS other_user_pseudo,
                CASE
                    WHEN c.user_one_id = :user_id_case THEN user_two.avatar
                    ELSE user_one.avatar
                END AS other_user_avatar,
                last_message.content AS last_message_content,
                last_message.created_at AS last_message_created_at
            FROM conversations c
            INNER JOIN users user_one
                ON user_one.id = c.user_one_id
            INNER JOIN users user_two
                ON user_two.id = c.user_two_id
            LEFT JOIN messages last_message
                ON last_message.id = (
                    SELECT message.id
                    FROM messages message
                    WHERE message.conversation_id = c.id
                    ORDER BY message.created_at DESC, message.id DESC
                    LIMIT 1
                )
            WHERE :user_id_filter IN (
                c.user_one_id,
                c.user_two_id
            )
            ORDER BY COALESCE(
                last_message.created_at,
                c.created_at
            ) DESC'
        );

        $query->execute([
            'user_id_case' => $userId,
            'user_id_filter' => $userId
        ]);

        return $query->fetchAll();
}

    //Récupère une conversation appartenant à un utilisateur
    public function findByIdForUser(
        int $conversationId,
        int $userId
    ): ?Conversation {
        $query = $this->connection->prepare(
            'SELECT * FROM conversations
            WHERE id = :id
            AND (user_one_id = :user_one_id
            OR user_two_id = :user_two_id)
            LIMIT 1'
        );

        $query->execute([
            'id' => $conversationId,
            'user_one_id' => $userId,
            'user_two_id' => $userId
        ]);

        $data = $query->fetch();

        if ($data === false) {
            return null;
        }

        return $this->hydrate($data);
    }
}