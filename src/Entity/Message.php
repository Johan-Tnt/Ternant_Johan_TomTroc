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

    public function __construct(
        int $conversationId,
        int $senderId,
        string $content
    ) {
        $this->conversationId = $conversationId;
        $this->senderId = $senderId;
        $this->content = $content;
    }

    //MESSAGE
    public function getId(): int
    {
        return $this->id;
    }

    //Définit l'identifiant récupéré depuis la base
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getConversationId(): int
    {
        return $this->conversationId;
    }

    public function setConversationId(int $conversationId): void
    {
        $this->conversationId = $conversationId;
    }

    public function getSenderId(): int
    {
        return $this->senderId;
    }

    public function setSenderId(int $senderId): void
    {
        $this->senderId = $senderId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
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