<section>
    <div class="container auth_container">

        <div class="auth_content">

            <h1>Mot de passe oublié</h1>

            <p class="forgot_reset_password_intro">
                Saisissez votre adresse e-mail pour recevoir un lien
                vous permettant de réinitialiser votre mot de passe.
            </p>

            <form
                class="auth_form"
                method="POST"
                action="index.php?route=forgot-password"
            >
                <div class="form_group">
                    <label for="email">Adresse e-mail</label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        autocomplete="email"
                        required
                    >
                </div>

                <?php if (isset($message)) : ?>
                    <p class="success_message forgot_reset_password_message">
                        <?= htmlspecialchars($message) ?>
                    </p>
                <?php endif; ?>

                <?php if (isset($error)) : ?>
                    <p class="error_message forgot_reset_password_error">
                        <?= htmlspecialchars($error) ?>
                    </p>
                <?php endif; ?>

                <button
                    type="submit"
                    class="button button--primary auth_button"
                >
                    Envoyer le lien
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