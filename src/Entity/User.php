<?php

namespace App\Entity;

class User
{
    //Identifiant généré par la base de données
    private int $id;
    
    private string $pseudo;
    private string $email;
    private string $password;
    private ?string $avatar;

    //Date générée par la base de données
    private string $createdAt;

    public function __construct(
        string $pseudo,
        string $email,
        string $password,
        ?string $avatar = null
    ) {
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->password = $password;
        $this->avatar = $avatar;
    }

    //USER ID
    public function getId(): int
    {
        return $this->id;
    }

    //Définit l'identifiant récupéré depuis la base
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    //USER PSEUDO
    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    //USER E-MAIL
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    //USER PASSWORD
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    //USER AVATAR
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
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