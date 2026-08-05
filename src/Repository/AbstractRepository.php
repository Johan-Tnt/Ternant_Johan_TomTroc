<?php

namespace App\Repository;

use App\Service\Database;
use PDO;

abstract class AbstractRepository
{
    //Connexion à la base de données 
    protected PDO $connection;

    public function __construct()
    {
     $this->connection = Database::getInstance()->getConnection();

    }

    //Nom de la table utilisée 
    abstract protected function getTableName(): string;

    //Transforme les données SQL en objet
    abstract protected function hydrate(array $data): mixed;

    //Récupère tous les éléments de la table
    public function findAll(): array
    {
        $query = $this->connection->query(
            'SELECT * FROM ' . $this->getTableName()
        );

        $results = [];

        while ($data = $query->fetch()) {
            $results[] = $this->hydrate($data);
        }

        return $results;
    }

    //Récupère un élément grâce à son identifiant
    public function findById(int $id): mixed
    {
        $query = $this->connection->prepare(
            'SELECT * FROM ' . $this->getTableName() . ' WHERE id = :id'
        );

        $query->execute([
            'id' => $id
        ]);

        $data = $query->fetch();

        if ($data === false) {
            return null;
        }

        return $this->hydrate($data);
    }
}