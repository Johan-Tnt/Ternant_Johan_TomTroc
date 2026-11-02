<section>
    <div class="container auth_container">

        <div class="auth_content">
            <h1>Réinitialiser le mot de passe</h1>

            <p class="forgot_reset_password_intro">
                Saisissez votre nouveau mot de passe,
                puis confirmez-le.
            </p>

            <form
                class="auth_form"
                method="POST"
                action="index.php?route=reset-password"
            >

                <input
                    type="hidden"
                    name="token"
                    value="<?=  htmlspecialchars(
                        $token ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

                <div class="form_group">
                    <label for="password">Nouveau mot de passe</label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >
                </div>

                <div class="form_group">
                    <label for="password_confirmation">
                        Confirmer le nouveau mot de passe
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >
                </div>

                <?php if (isset($error)) : ?>
                    <p class="error_message forgot_reset_password_error">
                        <?= htmlspecialchars($error) ?>
                    </p>
                <?php endif; ?>

                <button 
                    type="submit"
                    class="button button--primary auth_button"
                >
                    Modifier mon mot de passe
                </button>
            </form>

            <p class="login_register">
                 <a href="index.php?route=login">
                    Retour à la connexion
                 </a>
            </p>

        </div>

        <div class="auth_picture">
            <img
                src="assets/images/register-and-login/marialaura-gionfriddo.png"
                alt="Bibliothèque remplie de livres"
            >
        </div>

    </div>
</section>