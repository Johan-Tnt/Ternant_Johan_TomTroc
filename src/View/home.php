<!-- PASSIONATE READERS -->
<section class="home">
    <div class="container home_container">
    
        <div class="home_content">
            <h1>Rejoignez nos lecteurs passionnés</h1>

            <p>
                Donnez une nouvelle vie à vos livres en les échangeant avec 
                d'autres amoureux de la lecture. Nous croyons en la magie du 
                partage de connaissances et d'histoires à travers les livres. 
            </p>

            <a href="/books" class="button button--primary button_home">
                Découvrir
            </a>
        </div>

        <div class="home_image">
            <img src="assets/images/hamza-nouasria.png" 
            alt="Une pile de livres">

            <p class="written--secondary">Hamza</p>
        </div>

    </div>
</section>

<!-- DERNIERS LIVRES -->
<section class="home latest_books">

    <h2>Les derniers livres ajoutés</h2>

    <div class="container home_container">
    
        <?php foreach ($latestBooks as $book) : ?>

        <a  
            href="index.php?route=book-details&id=<?= $book['id'] ?>"
            class="book_link"
        >

        <article class="book_card">

            <img
                src="assets/images/pictures-books/<?= htmlspecialchars($book['picture']) ?>"
                alt="Couverture du livre <?= htmlspecialchars($book['title']) ?>"
            >

            <h3>
                <?= htmlspecialchars($book['title']) ?>
            </h3>

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

<!-- COMMENT ÇA MARCHE -->
<section class="home how">

    <div class="container">
        <h2>Comment ça marche ?</h2>

            <p>
                Échanger des livres avec TomTroc c’est simple et amusant ! 
                Suivez ces étapes pour commencer :
            </p>

            <ol class="how_works">
                <li class="instructions_works">Inscrivez-vous gratuitement sur notre plateforme.</li>

                <li class="instructions_works">Ajoutez les livres que vous souhaitez échanger à votre profil.</li>
                
                <li class="instructions_works">Parcourez les livres disponibles chez d'autres membres.</li>
                
                <li class="instructions_works">Proposez un échange et discutez avec d'autres passionnés de lecture.</li>
            </ol>

            <div class="books_look">
                <a href="/books" class="button button--outline button_home">
                    Voir tous les livres
                </a>
            </div>
    </div>

    <div>
            <img src="assets/images/banner-home.png" 
            alt="Une femme observant des piles de livres"> 
    </div>
</section>

<!-- NOS VALEURS -->
<section class="home our_values">

    <div class="container">
        <h2>Nos valeurs</h2>

            <p>
            Chez Tom Troc, nous mettons l'accent sur le partage, la découverte et la communauté. 
            Nos valeurs sont ancrées dans notre passion pour les livres et notre désir de créer des liens entre les lecteurs. 
            Nous croyons en la puissance des histoires pour rassembler les gens et inspirer des conversations enrichissantes.
            </p> 

            <p>
            Notre association a été fondée avec une conviction profonde : 
            chaque livre mérite d'être lu et partagé. 
            </p>

            <p>
            Nous sommes passionnés par la création d'une plateforme conviviale qui permet aux lecteurs de se connecter, 
            de partager leurs découvertes littéraires et d'échanger des livres qui attendent patiemment sur les étagères.
            </p>

            <div class="team-signature">
                <p class="written--secondary team">
                    L’équipe Tom Troc
                </p>

                <img
                   src="assets/images/green-heart.svg"
                   alt=""
                   class="heart-icon"
                   aria-hidden="true">
            </div>
    </div>

</section>
