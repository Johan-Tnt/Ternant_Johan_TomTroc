<?php

namespace App\Service;

class BookPictureService extends ImageService
{
    //Retourne le dossier des images des livres 
    protected function getUploadDirectory(): string
    {
        return __DIR__ . '/../../public/assets/images/pictures-books/';
    }

    //Retourne l'image par défaut des livres 
    protected function getDefaultImage(): string
    {
        return 'default-book.jpg';
    }

    //Retourne le préfixe  des images des livres
    protected function getFilePrefix(): string
    {
        return 'book_';
    }
}