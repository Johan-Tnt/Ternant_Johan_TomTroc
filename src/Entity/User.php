<?php

namespace App\Entity;

class User
{
    //Identifiant généré par la base de données
    private int $id = 0;
    
    private string $pseudo;
    private string $email;
    private string $password;
    private ?string $avatar;

    //Date générée par la base de données
    private string $createdAt = '';

    //Construit un nouvel utilisateur
    public function __construct(
        string $pseudo = '',
        string $email = '',
        string $password = '',
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
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    //USER PSEUDO
    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    //Définit le pseudo de l'utilisateur
    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    //USER E-MAIL
    public function getEmail(): string
    {
        return $this->email;
    }

    //Définit l'adresse e-mail de l'utilisateur
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    //USER PASSWORD
    public function getPassword(): string
    {
        return $this->password;
    }

    //Définit le mot de passe de l'utilisateur
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    //USER AVATAR
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    //Définit l'avatar de l'utilisateur
    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    //USER CREATED AT
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