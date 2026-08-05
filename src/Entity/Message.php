<?php

namespace App\Entity;

class Message
{
    //Identifiant généré par la base de données
    private int $id;
    
    private int $conversationId;
    private int $senderId;
    private string $content;

    //Date générée par la base de données
    private string $createdAt;
    
    //Construit un nouveau message
    public function __construct(
        int $conversationId,
        int $senderId,
        string $content
    ) {
        $this->conversationId = $conversationId;
        $this->senderId = $senderId;
        $this->content = $content;
    }

    //MESSAGE ID
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

    public function getConversationId(): int
    {
        return $this->conversationId;
    }

    public function setConversationId(int $conversationId): self
    {
        $this->conversationId = $conversationId;

        return $this;
    }

    public function getSenderId(): int
    {
        return $this->senderId;
    }

    public function setSenderId(int $senderId): self
    {
        $this->senderId = $senderId;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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