<?php

namespace App\Entity;

class Book
{
    //Identifiant généré par la base de données
    private int $id = 0;

    private int $userId;
    private string $title;
    private string $author;
    private ?string $description;
    private ?string $picture;
    private string $availability;

    //Dates générées par la base de données
    private string $createdAt = '';
    private string $updatedAt = '';

    //Construit un nouveau livre
    public function __construct(
        int $userId = 0,
        string $title = '',
        string $author = '',
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
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    //BOOK TITLE
    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    //BOOK AUTHOR
    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    //BOOK DESCRIPTION
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    //BOOK PICTURE
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getAvailability(): string
    {
        return $this->availability;
    }

    public function setAvailability(string $availability): self
    {
        $this->availability = $availability;

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

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    //Définit la date récupérée depuis la base
    public function setUpdatedAt(string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}