<section class="error_page">

    <div class="container error_container">

        <h1>404</h1>

        <h2><?= htmlspecialchars($errorTitle) ?></h2>

        <p>
            <?= htmlspecialchars($errorMessage) ?>
        </p>

        <a
            href="<?= htmlspecialchars($errorLink) ?>"
            class="button button--primary"
        >
            <?= htmlspecialchars($errorLinkText) ?>
        </a>

    </div>

</section>