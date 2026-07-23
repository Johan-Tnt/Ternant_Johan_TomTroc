<?php

namespace App\Controller;

use App\Service\View;

class BookController
{
    public function index(): void
    {
        View::getInstance()->render('books', 'Nos livres');
    }

    public function show(): void
    {
        //Simulation d'un livre venant de la base de données
        $books = [
            1 => [
            "id" => 1,
            "title" => "Esther",
            "author" => "Alabaster",
            "image" => "assets/images/pictures-books/esther.png",
            "description" => "Livre Esther.",
            "owner" => "CamilleClubLit"
            ],

            2 => [
            "id" => 2,
            "title" => "The Kinfolk Table",
            "author" => "Nathan Williams",
            "image" => "assets/images/pictures-books/the-kingfolk-table.png",
            "description" => "J'ai récemment plongé dans les pages de 'The Kinfolk Table' et 
            j'ai été enchanté par cette œuvre captivante. Ce livre va bien au-delà d'une simple 
            collection de recettes ; il célèbre l'art de partager des moments authentiques autour de la table. 
            Les photographies magnifiques et le ton chaleureux captivent dès le départ, transportant le lecteur 
            dans un voyage à travers des recettes et des histoires qui mettent en avant la beauté de 
            la simplicité et de la convivialité. Chaque page est une invitation à ralentir, à savourer et 
            à créer des souvenirs durables avec les êtres chers. 'The Kinfolk Table' incarne parfaitement l'esprit 
            de la cuisine et de la camaraderie, et il est certain que ce livre trouvera une place spéciale dans 
            le cœur de tout amoureux de la cuisine et des rencontres inspirantes.",
            "owner" => "Alexlecture"
            ]
        ];

        $id = $_GET['id'] ?? 1;

        $book = $books[$id] ?? $books[1];


        View::getInstance()->render(
            'book-details',
            'Détails du livre',
            [
            'book' => $book
            ]
        );
    }
}