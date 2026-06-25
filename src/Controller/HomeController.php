<?php

class HomeController
{
    public function index(): void
    {
        require_once __DIR__ . '/../View/home.php';
    }
}