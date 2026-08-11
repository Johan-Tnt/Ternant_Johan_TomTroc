<section class="book_details">

    <div class="container book_details_container">

        <img
            src="assets/images/pictures-books/<?= htmlspecialchars($book['picture']) ?>"
            alt="Couverture du livre <?= htmlspecialchars($book['title']) ?>"
            class="img_details"
        >

        <div class="book_details_content">

            <h1>
                <?= htmlspecialchars($book['title']) ?>
            </h1>

            <p class="written--secondary book_author">
                par <?= htmlspecialchars($book['author']) ?>
            </p>

            <div class="book_separator"></div>

            <h2 class="book_label">Description</h2>

            <p class="book_description">
                <?= htmlspecialchars($book['description']) ?>
            </p>

            <h2 class="book_label">PROPRIETAIRE</h2>

            <div class="owner_card">

                <img
                    src="assets/images/avatars/david-lezcano.png"
                    alt="Photo de <?= htmlspecialchars($book['pseudo']) ?>"
                    class="owner_avatar"
                >

                <p class="owner_name">
                    <?= htmlspecialchars($book['pseudo']) ?>
                </p>

            </div>

            <a href="#" class="button button--primary book_button">
                Envoyer un message
            </a>

        </div>

    </div>

</section>