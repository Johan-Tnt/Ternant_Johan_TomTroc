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

    //Construit une nouvelle conversation
    public function __construct(
        int $userOneId,
        int $userTwoId
    ) {
        $this->userOneId = $userOneId;
        $this->userTwoId = $userTwoId;
    }

    //CONVERSATION ID
    public function getId(): int
    {
        return $this->id;
    }

    //Définit l'identifiant récupéré depuis la base
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUserOneId(): int
    {
        return $this->userOneId;
    }

    public function setUserOneId(int $userOneId): self
    {
        $this->userOneId = $userOneId;

        return $this;
    }

    public function getUserTwoId(): int
    {
        return $this->userTwoId;
    }

    public function setUserTwoId(int $userTwoId): self
    {
        $this->userTwoId = $userTwoId;

        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    //Définit la date récupérée depuis la base
    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}