<?php

namespace App\Service;

class UserPictureService extends ImageService
{
    //Retourne le dossier des avatars 
    protected function getUploadDirectory(): string
    {
        return __DIR__ . '/../../public/assets/images/avatars/';
    }

    //Retourne l'avatar par défaut
    protected function getDefaultImage(): string
    {
        return 'default-avatar.jpg';
    }

    //Retourne le préfixe des avatars 
    protected function getFilePrefix(): string
    {
        return 'avatar_';
    }
}