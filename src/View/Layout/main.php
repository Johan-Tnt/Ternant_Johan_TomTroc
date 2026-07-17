<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'TomTroc'; ?></title>

    <link rel="stylesheet" href="assets/css/base/style.css">
    <link rel="stylesheet" href="assets/css/components/header.css">
    <link rel="stylesheet" href="assets/css/components/footer.css">
    <link rel="stylesheet" href="assets/css/pages/home.css">
    <link rel="stylesheet" href="assets/css/components/button.css">
    <link rel="stylesheet" href="assets/css/components/book-card.css">
    <link rel="stylesheet" href="assets/css/pages/books.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,600;1,400&family=Playfair+Display:wght@400&display=swap"
    rel="stylesheet">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    
</head>

<body>

    <?php require_once __DIR__ . '/header.php'; ?>

    <main>
        <?= $content ?>
    </main>

    <?php require_once __DIR__ . '/footer.php'; ?>

</body>

</html>