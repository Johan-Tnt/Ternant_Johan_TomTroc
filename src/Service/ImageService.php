<?php

namespace App\Service;

abstract class ImageService extends Singleton
{
    private const MAX_FILE_SIZE = 5 * 1024 * 1024;

    private const ALLOWED_TYPES = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];

    //Retourne le dossier dans lequel enregistrer les images
    abstract protected function getUploadDirectory(): string;

    //Retourne le nom de l'image par défaut 
    abstract protected function getDefaultImage(): string;

    //Retourne le préfixe utilisé pour nommer les images
    abstract protected function getFilePrefix(): string;

    //Enregistre une image dans le dossier
    public function upload(
            string $inputName = 'picture',
            bool $useDefault = true
        ): array {

        if (
            !isset($_FILES[$inputName])
            || $_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE
        ) {
            if ($useDefault) {
                return [
                    'file' => $this->getDefaultImage(),
                    'error' => null
                ];
            }

            return [
                'file' => null,
                'error' => null
            ];
        }

        $file = $_FILES[$inputName];

        //Vérifie les erreurs d'upload
        if ($file['error'] !== UPLOAD_ERR_OK) {

            $error = 'Une erreur inconnue est survenue lors de l’envoi de l’image.';

            return [
                'file' => null,
                'error' => $error
            ];
        }

        //Vérifie la taille
        if ($file['size'] > self::MAX_FILE_SIZE) {
            return [
                'file' => null,
                'error' => 'L’image ne doit pas dépasser 5 Mo.'
            ];
        }

        //Vérifie le type réel du fichier
        $fileType = mime_content_type($file['tmp_name']);

        if (!isset(self::ALLOWED_TYPES[$fileType])) {
            return [
                'file' => null,
                'error' =>
                    'Le format de l’image n’est pas valide. Formats acceptés : JPG, PNG et WEBP.'
            ];
        }

        //Crée le dossier si nécessaire
        if (!is_dir($this->getUploadDirectory())) {
            mkdir($this->getUploadDirectory(), 0777, true);
        }

        $extension = self::ALLOWED_TYPES[$fileType];

        $fileName = uniqid($this->getFilePrefix(), true) . '.' . $extension;

        $uploadPath = $this->getUploadDirectory() . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return [
                'file' => null,
                'error' => null
            ];
        }

        return [
            'file' => $fileName,
            'error' => null
        ];
    }

    //Supprime une image du dossier
    public function delete(?string $fileName): void
    {
        if (
            empty($fileName)
            || $fileName === $this->getDefaultImage()
        ) {
            return;
        }

        $filePath = $this->getUploadDirectory() . $fileName;

        if (
            file_exists($filePath)
            && is_file($filePath)
        ) {
            unlink($filePath);
        }
    }
}