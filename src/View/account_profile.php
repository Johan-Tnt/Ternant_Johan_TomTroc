<section class="account_section">

<div class="container account_container">

    <div class="account_profile_content">

        <!-- PROFIL PUBLIC-->
        <aside class="account_profile">

            <div class="account_avatar_container">

           <img 
                src="assets/images/avatars/<?= htmlspecialchars(
                     $user->getAvatar() ?: 'default-avatar.jpg'
                ) ?>"
                alt="Photo de <?= htmlspecialchars(
                    $user->getPseudo()
                ) ?>"
                class="account_avatar"
            >

    </div>

            <div class="account_profile_separator"></div>

            <h1>
                <?=htmlspecialchars($user->getPseudo()) ?>
            </h1>

            <p class="written--secondary">
                Membre depuis 1 an
            </p>

            <div class="account_books_count">

                <span class="account_books_label">
                     BIBLIOTHÈQUE
                </span>

            <div class="account_books_info">

                <span class="account_books_icon">
                    <img
                        src="assets/images/icon-two-books.svg"
                        alt=""
                        aria-hidden="true"
                        class="account_books_icon"
                >
                </span>

                <span>
                    <?= $bookCount ?>
                    livre<?= $bookCount > 1 ? 's' : '' ?>
                </span>

            </div>

            <a
                href="#"
                class="button button--outline--account account_button account_profile_message"
            >
                Écrire un message
            </a>

        </aside>
    
    <!-- LIVRES -->
    <section class="account_books">

        <?php if (empty($books)) : ?>

            <p class="account_empty_books">
                Cet utilisateur n'a pas encore ajouté de livre.
            </p>

        <?php else : ?>
        
            <div class="account_books_table account_profile_books_table">

                <div class="account_books_table_header">

                    <span>Photo</span>
                    <span>titre</span>
                    <span>Auteur</span>
                    <span>Description</span>

                </div>

                <?php foreach ($books as $book) : ?>

                    <div class="account_book_row">

                        <div class="account_book_image">

                            <img 
                                src="assets/images/pictures-books/<?= htmlspecialchars(
                                    $book->getPicture() ?: 'default-book.jpg'
                                ) ?>" 
                                alt="Couverture du livre <?= htmlspecialchars(
                                    $book->getTitle()
                                ) ?>" 
                            >

                        </div>

                        <div class="account_book_title">

                            <?=  htmlspecialchars(
                                $book->getTitle()
                            ) ?>

                        </div>

                        <div class="account_book_author">

                            <?=  htmlspecialchars(
                                $book->getAuthor()
                            ) ?>

                        </div>

                        <div class="account_book_description">

                            <?=  htmlspecialchars(
                                $book->getDescription()
                            ) ?>

                        </div>

                    </div>

                <?php endforeach; ?>

           </div>

        <?php endif; ?>

    </section>

</div>

</section>