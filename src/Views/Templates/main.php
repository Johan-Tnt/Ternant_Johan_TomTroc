<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= $title ?? 'TomTroc'; ?></title>

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >
</head>

<body>

    <?php require_once __DIR__ . '/header.php'; ?>

    <main>
        <?= $content ?>
    </main>

    <?php require_once __DIR__ . '/footer.php'; ?>

</body>

</html>