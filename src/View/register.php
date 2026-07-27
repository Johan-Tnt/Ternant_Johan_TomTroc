<section>

    <div class="container register_container">

        <div class="register_content">

            <h1>Inscription</h1>

            <form class="register_form" method="POST" action="?route=register">
            
                <div class="form_group">
                    <label for="pseudo">Pseudo</label>

                    <input 
                        type="text"
                        id="pseudo"
                        name="pseudo"
                        required>
                </div>

                <div class="form_group">
                    <label for="email">Adresse email</label>

                    <input 
                        type="email"
                        id="Adresse email"
                        name="Adresse email"
                        required>
                </div>
            
                <div class="form_group">
                    <label for="password">Mot de passe</label>

                    <input 
                        type="password"
                        id="password"
                        name=" password"
                        required>
                </div>

                <button
                    type="submit"
                    class="button button--primary">
                    S'inscrire
                </button>

            </form>

            <p class="register_login">
                Déjà Inscrit ?
                <a class="?rout=login">Connectez-vous</a>
            </p>

        </div>

        <div class="register_picture">
            <img 
                src="assets/images/register-and-login/marialaura-gionfriddo.png"
                alt="Bibliotèque  rempli de livres">
        </div>

   </div>

</section>