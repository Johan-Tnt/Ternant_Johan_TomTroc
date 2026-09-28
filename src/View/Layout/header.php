<?php

use App\Repository\MessageRepository;

$route = $_GET['route'] ?? '';

$unreadMessageCount = 0;

if (isset($_SESSION['user_id'])) {
    $messageRepository = new MessageRepository();

    $unreadMessageCount = $messageRepository->countUnreadMessages(
        (int) $_SESSION['user_id']
    );
}
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

                <?php if (isset($_SESSION['user_id'])): ?>

                    <li>
                        <a href="index.php?route=messaging"
                             class="<?= $route === 'messaging' ? 'active' : '' ?>"
                        >
                            Messagerie
                            
                            <?php if ($unreadMessageCount > 0) : ?>
                                <span class="messaging_unread_count">
                                    <?= $unreadMessageCount ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>

                    <li>
                        <a href="index.php?route=account"
                            class="<?= $route === 'account' ? 'active' : '' ?>"
                        >
                            <?= htmlspecialchars($_SESSION['pseudo']) ?>
                        </a>
                    </li>

                    <li>
                        <a href="index.php?route=logout">
                            Déconnexion
                        </a>
                    </li>

                <?php else: ?>

                    <li>
                        <a href="index.php?route=register" class="<?= $route === 'register' ? 'active' : '' ?>">
                            Inscription
                        </a>
                    </li>

                    <li>
                        <a href="index.php?route=login" class="<?= $route === 'login' ? 'active' : '' ?>">
                            Connexion
                        </a>
                    </li>

                <?php endif; ?>

            </ul>
        </nav>
    </div>
</header>