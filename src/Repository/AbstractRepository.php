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

    //Nom de la classe de l'entité utilisée
    abstract protected function getEntityClass(): string;

    //Transforme les données SQL en objet
    protected function hydrate(array $data): object
    {
        //Récupère la classe de l'entité
        $class = $this->getEntityClass();

        //Crée une nouvelle instance de l'entité
        $entity = new $class();

        foreach ($data as $key => $value) {

            //Transforme les noms SQL en setters PHP
            $setter = 'set' . str_replace(
                ' ',
                '',
                ucwords(str_replace('_', ' ', $key))
            );

            if (method_exists($entity, $setter)) {
                $entity->$setter($value);
            }
        }

        return $entity;
    }

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
    public function findById(int $id): ?object
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