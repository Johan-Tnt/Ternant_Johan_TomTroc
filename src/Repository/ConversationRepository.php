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
                    WHEN c.user_one_id = :user_id_one THEN u2.id
                    ELSE u1.id
                END AS other_user_id,
                CASE 
                    WHEN c.user_one_id = :user_id_two THEN u2.pseudo
                    ELSE u1.pseudo 
                END AS other_user_pseudo,
                CASE 
                    WHEN c.user_one_id = :user_id_three THEN u2.avatar
                    ELSE u1.avatar
                END AS other_user_avatar,
                m.content AS last_message_content,
                m.created_at AS last_message_created_at
            FROM conversations c
            INNER JOIN users u1 ON u1.id = c.user_one_id
            INNER JOIN users u2 ON u2.id = c.user_two_id
            LEFT JOIN messages m ON m.id = (
                SELECT m2.id
                FROM messages m2 
                WHERE m2.conversation_id = c.id
                ORDER BY m2.created_at DESC, m2.id DESC
                LIMIT 1
            )
            WHERE c.user_one_id = :user_id_four
            OR c.user_two_id = :user_id_five
            ORDER BY COALESCE(m.created_at, c.created_at) DESC'
        );

        $query->execute([
            'user_id_one' => $userId,
            'user_id_two' => $userId,
            'user_id_three' => $userId,
            'user_id_four' => $userId,
            'user_id_five' => $userId
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