<?php

namespace App\Service;

class ImageService 
{
    private const MAX_FILE_SIZE = 5 * 1024 * 1024;

    private const ALLOWED_TYPES = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];

    private const UPLOAD_DIRECTORY =
        __DIR__ . '/../../public/assets/images/pictures-books/';

    //Ajoute une image par defaut
    private const DEFAULT_IMAGE = 'default-book.jpg';

    //Enregistre une image dans le dossier des livres
    public function upload(
        string $inputName = 'picture'
        ): ?string {

        if (
            !isset($_FILES[$inputName])
            || $_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE
        ) {
            return self::DEFAULT_IMAGE;
        }

        if ($_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        if (
            $_FILES[$inputName]['size'] 
            > self::MAX_FILE_SIZE
        ) {
            return null;
        }

        $fileType = mime_content_type(
            $_FILES[$inputName]['tmp_name']
        );

        if (!isset(self::ALLOWED_TYPES[$fileType])) {
            return null;
        }

        if (!is_dir(self::UPLOAD_DIRECTORY)) {
            mkdir(self::UPLOAD_DIRECTORY, 0777, true);
        }

        $fileName = uniqid() . '.' . self::ALLOWED_TYPES[$fileType];

        $uploadPath = self::UPLOAD_DIRECTORY . $fileName;
        
        if (!move_uploaded_file(
            $_FILES[$inputName]['tmp_name'],
            $uploadPath
        )) {
            return null;
        }

        return $fileName;
    }

    //Supprime une image du dossier
    public function delete(?string $fileName): void 
    {
        if (
            empty($fileName)
            || $fileName === self::DEFAULT_IMAGE
        ) {
            return;
        }

        $filePath = self::UPLOAD_DIRECTORY . $fileName;

        if (
            file_exists($filePath)
            && is_file($filePath)
        ) {
            unlink($filePath);
        }
    }
}