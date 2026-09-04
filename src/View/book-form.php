<section class="book_form_section">

   <div class="book_form_header">
        <a
            href="javascript:history.back()"
            class="back_link written--secondary "
        >
            ← retour
        </a>

        <h1><?= htmlspecialchars($formTitle) ?></h1>

    </div>

    <div class="container book_form_container">

        <form
            class="book_form"
            method="POST"
            action="index.php?route=<?= htmlspecialchars($formAction) ?>"
            enctype="multipart/form-data"
        >
            
            <div class="book_form_photo">

                <p class="book_form_label written--secondary">
                   Photo
                </p>

                <img
                    src="assets/images/pictures-books/<?= htmlspecialchars(
                        $book['picture'] ?? 'default-book.jpg'
                    ) ?>"
                    alt="Couverture du livre <?= htmlspecialchars($book['title'] ?? '') ?>"
                >

                <label for="picture" class="book_photo_link">
                    Modifier la photo
                </label>

                <input
                type="file"
                id="picture"
                name="picture"
                accept="image/*"
                hidden
                >
            
                <?php if (!empty($error)) : ?>

                    <p class="form_error">
                        <?= htmlspecialchars($error) ?>
                    </p>

                <?php endif; ?>

            </div>

            <div class="book_form_fields">

                <div class="form_group written--secondary">

                    <label for="title">
                        Titre
                    </label>

                    <input
                        type="text"
                       id="title"
                        name="title"
                        value="<?= htmlspecialchars($book['title'] ?? '') ?>"
                        required
                    >

                </div>

                <div class="form_group written--secondary">

                    <label for="author">
                        Auteur
                    </label>

                    <input
                        type="text"
                        id="author"
                        name="author"
                        value="<?= htmlspecialchars($book['author'] ?? '') ?>"
                        required
                    >

                </div>

                <div class="form_group written--secondary">

                    <label for="description">
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="8"
                    ><?= htmlspecialchars($book['description'] ?? '') ?></textarea>

                 </div>


                <div class="form_group written--secondary">

                    <label for="availability">
                        Disponibilité
                    </label>

                   <select
                        id="availability"
                        name="availability"
                    >

                        <option
                            value="available"
                            <?= ($book['availability'] ?? 'available') === 'available' ? 'selected' : '' ?>
                        >
                            disponible
                        </option>

                        <option
                            value="unavailable"
                            <?= ($book['availability'] ?? '') === 'unavailable' ? 'selected' : '' ?>
                        >
                            non disponible
                        </option>

                    </select>

                </div>

                <button
                    type="submit"
                    class="button button--primary button_validator"
                >
                    Valider
                </button>

            </div>

        </form>

    </div>

</section>