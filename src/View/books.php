<!-- OUR BOOKS FOR EXCHANGE AND WANTED -->
<section class="books_and_search">
    <div class="container books_header">

        <h1>Nos Livres à l'échange</h1>

        <form class="search_form" action="index.php" method="get">

            <input type="hidden" name="route" value="books">

            <div class="search_input">

                <i class="fa-solid fa-magnifying-glass search_icon"></i>

                <input
                    class="search_field"
                    type="search"
                    name="search"
                    placeholder="Rechercher un livre"
                    value="<?=htmlspecialchars($_GET['search'] ?? '') ?>"
                >
            
            </div>

        </form>

    </div>

</section>

<!-- LIST OF BOOKS -->
<section class="books_list">

    <div class="container home_container books_container">

    <?php foreach ($books as $book) : ?>

        <a
            href="index.php?route=book-details&id=<?= $book['id'] ?>"
            class="book_link"
        >

            <article class="book_card exchange_books">

                <img
                    src="assets/images/pictures-books/<?= htmlspecialchars($book['picture']) ?>"
                    alt="Couverture du livre <?= htmlspecialchars($book['title']) ?>"
                >
                
                <?php if ($book['availability'] === 'unavailable') : ?>

                    <span class="button button--tertiary button_unavailable">
                        non dispo.
                    </span>

                <?php endif; ?>

                <h2>
                    <?= htmlspecialchars($book['title']) ?>
                </h2>

                <p class="written--secondary">
                    <?= htmlspecialchars($book['author']) ?>
                </p>

                <p class="written--secondary">
                    Vendu par :
                    <?= htmlspecialchars($book['pseudo']) ?>
                </p>

            </article>

        </a>

    <?php endforeach; ?>

    </div>

</section>