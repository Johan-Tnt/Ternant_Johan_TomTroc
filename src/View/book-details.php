<!-- BREADCRUMB -->
<div class="container breadcrumb">
    <a href="index.php?route=books">
        Nos livres
    </a>

    <span aria-hidden="true">></span>

    <span>
        <?= htmlspecialchars($book['title']) ?>
    </span>
</div>

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
                    src="assets/images/avatars/<?= htmlspecialchars($book['avatar']) ?>"
                    alt="Photo de <?= htmlspecialchars($book['pseudo']) ?>"
                    class="owner_avatar"
                >

                <p class="owner_name">
                    <?= htmlspecialchars($book['pseudo']) ?>
                </p>

            </div>
            
            <?php if (
                !isset($_SESSION['user_id'])
                || (int) $_SESSION['user_id'] !== (int) $book['user_id']
            ) : ?>

                <a href="#" class="button button--primary book_button">
                    Envoyer un message
                </a>

            <?php endif; ?>

            <?php if (
                isset($_SESSION['user_id'])
                && (int) $_SESSION['user_id'] === (int) $book['user_id']
            ) : ?>

                <a
                    href="index.php?route=book-edit&id=<?= $book['id'] ?>"
                    class="button button--primary book_button"
                >
                    Modifier le livre
                </a>

            <?php endif; ?>

        </div>

    </div>

</section>