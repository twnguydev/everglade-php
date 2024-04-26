<!DOCTYPE html>
<html lang="fr" class="dark-mode">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <title>Erreur ! &mdash; EvergladePHP</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-black text-white">
    <nav class="bg-gray-800 p-4 fixed top-0 left-0 right-0">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-white font-bold text-xl">EvergladePHP</a>
        </div>
    </nav>

    <div class="flex flex-col items-center justify-center" style="margin-top:200px">
        <div class="text-foreground font-semibold text-6xl tracking-tighter w-full max-w-lg mb-8">
            Erreur 500
        </div>
        <div class="relative w-full max-w-lg">
            <div class="relative -mb-px h-px w-full bg-gradient-to-r from-transparent via-sky-300 to-transparent" bis_skin_checked="1"></div>
            <div class="mx-5 border dark:border-b-white/50 dark:border-t-white/50 border-b-white/20 sm:border-t-white/20 shadow-[20px_0_20px_20px] shadow-slate-500/10 dark:shadow-white/20 rounded-lg border-white/20 border-l-white/20 border-r-white/20 sm:shadow-sm lg:rounded-xl lg:shadow-none">
                <div class="flex flex-col p-6">
                    <h3 class="text-xl font-semibold leading-6 tracking-tighter">Vous devriez jeter un coup d'oeil à ceci :</h3>
                    <p class="mt-8 text-sm font-medium text-white/50"><?= $message ?></p>
                    <p class="mt-6 text-sm font-medium text-white/50"><?= $frontMessage ?></p>
                </div>
                <div class="p-6 pt-0">
                    <a href="/" class="font-semibold hover:bg-black hover:text-white hover:ring hover:ring-white transition duration-300 inline-flex items-center justify-center rounded-md text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-white text-black h-10 px-4 py-2">Retour à l'accueil</a>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-gray-800 p-4 fixed bottom-0 right-0 left-0">
        <div class="container mx-auto text-center text-white">
            &copy; <?= date('Y') ?> EvergladePHP. Tous droits réservés.
        </div>
    </footer>

    <script src="/public/js/jquery.min.js"></script>
</body>

</html>