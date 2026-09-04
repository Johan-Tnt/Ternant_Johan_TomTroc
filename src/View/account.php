<section class="account_section">

<div class="container account_container">

    <h1>Mon compte</h1>

    <div class="account_content">

        <!-- PROFIL -->
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

            <form
                method="POST"
                action="index.php?route=account-update"
                enctype="multipart/form-data"
                class="account_avatar_form"
            >
            
                <label
                    for="avatar"
                    class="account_avatar_edit"
                >
                    modifier
                </label>

                <input
                    type="file"
                    id="avatar"
                    name="avatar"
                    accept="image/jpeg,image/png,image/webp"
                    hidden
                >

                <button 
                   type="submit"
                    class="account_avatar_edit"
                >
                   enregistrer
                </button>

            </form>

    </div>

            <div class="account_profile_separator"></div>

            <h2>
                <?=htmlspecialchars($user->getPseudo()) ?>
            </h2>

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

        </aside>

        <!-- INFORMATIONS PERSONNELLES -->
        <div class="account_information">

            <h2>
                Vos informations personnelles
            </h2>

            <?php if (!empty($error)) : ?>

                <p class="form_error">
                    <?= htmlspecialchars($error) ?>
                </p>

            <?php endif; ?>

            <form
                method="POST"
                action="index.php?route=account-update"
                class="account_form"
            >

                <div class="form_group written--secondary">

                    <label for="email">
                        Adresse e-mail
                    </label> 

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?=  htmlspecialchars(
                            $user->getEmail()
                        ) ?>" 
                        autocomplete="username"
                    >

                </div>

                <div class="form_group written--secondary">

                    <label for="password"> 
                        Mot de passe 
                    </label>

                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••" 
                        autocomplete="new-password"
                    > 

                </div>

                <div class="form_group written--secondary"> 
                
                    <label for="pseudo"> 
                        Pseudo 
                    </label> 

                   <input 
                        type="text" 
                        id="pseudo" 
                        name="pseudo" 
                        value="<?= htmlspecialchars( 
                                $user->getPseudo()
                            ) ?>" 
                            required 
                    > 

                </div>

                <button
                    type="submit"
                    class="button button--outline--account account_button"
                >
                    Enregistrer
                </button>

            </form>

        </div>

    </div>
    
    <!-- LIVRES -->
    <section class="account_books">

        <?php if (empty($books)) : ?>

            <p class="account_empty_books">
                Vous n'avez pas encore ajouté de livre.
            </p>

        <?php else : ?>
        
            <div class="account_books_table">

                <div class="account_books_table_header">

                    <span>Photo</span>
                    <span>titre</span>
                    <span>Auteur</span>
                    <span>Description</span>
                    <span>Disponibilité</span>
                    <span>Action</span>

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

                        <div class="account_book_availability">

                            <?php if ( 
                                $book->getAvailability() === 'available'
                            ) : ?> 

                                <span class="account_status available"> 
                                    disponible 
                                </span> 

                            <?php else : ?> 

                                <span class="account_status unavailable"> 
                                    non dispo. 
                                </span> 

                            <?php endif; ?>

                        </div>

                        <div class="account_book_actions"> 

                            <a 
                            href="index.php?route=book-edit&id=<?= $book->getId() ?>" 
                            > 
                                Éditer 
                            </a>

                            <form
                                method="POST"
                                action="index.php?route=book-delete"
                                onsubmit="return confirm('Voulez-vous vraiment supprimer ce livre ?');"
                            >

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= $book->getId() ?>"
                                >

                                <button
                                    type="submit"
                                    class="delete_book" 
                                > 
                                    Supprimer 
                                </button> 

                            </form>

                        </div>

                    </div>

                <?php endforeach; ?>

           </div>

        <?php endif; ?>

    </section>

</div>

</section>