<?php ob_start(); ?>

<div class="container">
    <h1>Rejoignez nos lecteurs passionnés</h1>
</div>

<div class="container">
    <p>Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture. 
    Nous croyons en la magie du partage de connaissances et d'histoires à travers les livres. 
    </p>
</div>

<?php
$content = ob_get_clean();

$title = 'Accueil';

require_once __DIR__ . '/template/main.php';