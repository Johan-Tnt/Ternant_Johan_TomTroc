<section class="book_details">

    <div class="container book_details_container">

        <img 
            src="<?= $book['image']; ?>" 
            alt="Couverture du livre <?= $book['title']; ?>"
            class="img_details">


        <div class="book_details_content">

            <h1><?= $book['title']; ?></h1>

                <p class="written--secondary book_author">
                par <?= $book['author']; ?>
                </p>

            <div class="book_separator"></div>

            <h2 class="book_label">Description</h2>

                <p class="book_description">
                <?= $book['description']; ?>
                </p>

           <h2 class="book_label">PROPRIETAIRE</h2>

            <div class="owner_card">
                
                <img
                    src="assets/images/avatars/david-lezcano.png"
                    alt="Photo de <?= $book['owner']; ?>"
                    class="owner_avatar">

                <p class="owner_name">
                    <?= $book['owner']; ?>
                </p>

            </div>

            <a href="#" class="button button--primary book_button">
               Envoyer un message
            </a>

        </div>

    </div>

</section>