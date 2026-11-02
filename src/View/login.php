<section>

    <div class="container auth_container">

        <div class="auth_content">

            <h1>Connexion</h1>

            <form action="index.php?route=login" method="POST" class="auth_form">
                
                <div class="form_group">
                    <label for="email">Adresse e-mail</label>

                    <input 
                        type="email"
                        id="email"
                        name="email"
                        autocomplete="email"
                        required>
                </div>

                <div class="form_group">
                    <label for="password">Mot de passe</label>

                    <input 
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        required>
                </div>

                <div class="login_options">
                    <label class="remember_me">

                        <input 
                            type="checkbox"
                            name="remember"
                            value="1">

                        <span>Se souvenir de moi</span>
                    </label>

                    <a href="index.php?route=forgot-password" class="forgot_password">
                        Mot de passe oublié ?
                    </a>

                    </div>

                    
            
                <?php if (isset($error)) : ?>

                    <p class="error_message">
                        <?= htmlspecialchars($error) ?>
                    </p>

                <?php endif; ?>

                <?php if (!empty($message)) : ?>

                    <p class="success_message forgot_reset_password_message">
                        <?= htmlspecialchars(
                            $message,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </p>

                <?php endif; ?>

                    <button 
                        type="submit"
                        class="button button--primary auth_button">
                        Se connecter
                    </button>

            </form>

            <p class="login_register">Pas de compte ?
                <a href="index.php?route=register">
                    Inscrivez-vous
                </a>
            </p>
            
        </div>

        <div class="auth_picture">
            <img 
                src="assets/images/register-and-login/marialaura-gionfriddo.png"
                alt="Bibliotèque remplie de livres">
        </div>


    </div>

</section>