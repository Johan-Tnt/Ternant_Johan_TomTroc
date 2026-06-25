<?php

spl_autoload_register(function (string $class): void {
    $directories = [
        __DIR__ . '/../Controller/',
        __DIR__ . '/../Model/',
        __DIR__ . '/../Entity/',
        __DIR__ . '/../Repository/',
        __DIR__ . '/../Service/',
        __DIR__ . '/../View/',
        __DIR__ . '/',
    ];

    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';

        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});