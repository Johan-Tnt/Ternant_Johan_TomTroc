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
}