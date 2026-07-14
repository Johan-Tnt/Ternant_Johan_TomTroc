<?php
$route = $_GET['route'] ?? '';
?>

<header>
    <div class="container">
        <div class="logo">
            <a href="index.php">
                <img  class="logo_tomtroc"
                src="assets/images/tomtroc-logo.svg" 
                alt="Logo Tom Troc">
                
                <span class="logo_text">Tom Troc</span>
            </a>
        </div>

        <nav>
            <ul>
                <li>
                    <a href="index.php" class="<?= $route === '' ? 'active' : '' ?>">
                        Accueil
                    </a>
                </li>

                <li>
                    <a href="index.php?route=books" class="<?= $route === 'books' ? 'active' : '' ?>">
                        Nos livres à l'échange
                    </a>
                </li>
            </ul>
        </nav>

        <nav>
            <ul>
                <li>
                    <a href="#">
                        Inscription
                    </a>
                </li>

                <li>
                    <a href="#">
                        Connexion
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>