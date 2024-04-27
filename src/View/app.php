<!DOCTYPE html>
<html lang="fr" class="dark-mode">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $app->description ?>">
    <meta name="keywords" content="<?= $app->keywords ?>">
    <meta name="author" content="<?= $app->author ?>">
    <title><?= $app->name ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <script src="/myapp/public/js/jquery.min.js"></script>
</head>

<body>
    
    <?= $navbar ?>
    <?= $content ?>
    <?= $footer ?>

</body>

</html>