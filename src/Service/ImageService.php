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

    //Définit l'image par defaut d'un livre
    private const DEFAULT_IMAGE = 'default-book.jpg';

    //Enregistre une image dans le dossier des livres
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
                    'file' => self::DEFAULT_IMAGE,
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

            $error = match ($file['error']) {
                UPLOAD_ERR_INI_SIZE,
                UPLOAD_ERR_FORM_SIZE =>
                    'L’image est trop volumineuse.',

                UPLOAD_ERR_PARTIAL =>
                    'L’image n’a pas été envoyée entièrement.',

                UPLOAD_ERR_NO_TMP_DIR =>
                    'Le dossier temporaire est introuvable.',

                UPLOAD_ERR_CANT_WRITE =>
                    'Impossible d’enregistrer l’image.',

                UPLOAD_ERR_EXTENSION =>
                    'L’envoi de l’image a été interrompu.',

                default =>
                    'Une erreur inconnue est survenue lors de l’envoi de l’image.'
            };

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
        if (!is_dir(self::UPLOAD_DIRECTORY)) {
            mkdir(self::UPLOAD_DIRECTORY, 0777, true);
        }

        $extension = self::ALLOWED_TYPES[$fileType];

        $fileName = uniqid('book_', true) . '.' . $extension;

        $uploadPath = self::UPLOAD_DIRECTORY . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return [
                'file' => null,
                'error' => 'Impossible d’enregistrer l’image.'
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