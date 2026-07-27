<?php

namespace App\Entity;


class Conversation
{
    //Identifiant généré par la base de données
    private int $id;
    
    private int $userOneId;
    private int $userTwoId;

    //Date générée par la base de données
    private string $createdAt;

    public function __construct(
        int $userOneId,
        int $userTwoId
    ) {
        $this->userOneId = $userOneId;
        $this->userTwoId = $userTwoId;
    }

    //CONVERSATION
    public function getId(): int
    {
        return $this->id;
    }

    //Définit l'identifiant récupéré depuis la base
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUserOneId(): int
    {
        return $this->userOneId;
    }

    public function setUserOneId(int $userOneId): void
    {
        $this->userOneId = $userOneId;
    }

    public function getUserTwoId(): int
    {
        return $this->userTwoId;
    }

    public function setUserTwoId(int $userTwoId): void
    {
        $this->userTwoId = $userTwoId;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    //Définit la date récupérée depuis la base
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}