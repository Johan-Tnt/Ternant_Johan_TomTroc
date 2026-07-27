<?php

namespace App\Entity;

class Book
{
    //Identifiant généré par la base de données
    private int $id;

    private int $userId;
    private string $title;
    private string $author;
    private ?string $description;
    private ?string $picture;
    private string $availability;

    //Dates générées par la base de données
    private string $createdAt;
    private string $updatedAt;

    public function __construct(
        int $userId,
        string $title,
        string $author,
        ?string $description = null,
        ?string $picture = null,
        string $availability = 'available'
    ) {
        $this->userId = $userId;
        $this->title = $title;
        $this->author = $author;
        $this->description = $description;
        $this->picture = $picture;
        $this->availability = $availability;
    }

    //BOOK ID
    public function getId(): int
    {
        return $this->id;
    }

    //Définit l'identifiant récupéré depuis la base
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    //BOOK TITLE
    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    //BOOK AUTHOR
    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    //BOOK DESCRIPTION
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    //BOOK PICTURE
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): void
    {
        $this->picture = $picture;
    }

    public function getAvailability(): string
    {
        return $this->availability;
    }

    public function setAvailability(string $availability): void
    {
        $this->availability = $availability;
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

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    //Définit la date récupérée depuis la base
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}