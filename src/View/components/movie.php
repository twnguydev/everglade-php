<div class="bg-black text-white flex min-h-screen flex-col items-center pt-16 sm:justify-center sm:pt-0">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-4 bg-black p-8">
        {# foreach (movies as key => value) #}
            <div class="w-full max-w-sm bg-black border border-gray-200 rounded-lg shadow">
                <img class="rounded-t-lg mb-3" src="/myapp/public/img/image.png" alt="product image" />
                <div class="px-5 pb-5">
                    <h5 class="text-xl font-semibold tracking-tight text-white">{echo_var=title}</h5>
                    <span class="text-sm font-semibold text-gray-200 dark:text-gray-600">Réalisateur: {echo_var=director}</span><br>
                    <span class="text-sm font-semibold text-gray-200 dark:text-gray-600">Genre: {echo_var=genre}</span><br>
                    <span class="text-sm font-semibold text-gray-200 dark:text-gray-600">Date de sortie: {echo_var=release_date}</span><br><br>
                    <span class="text-sm font-bold text-white">{echo_var=viewers}</span>
                    <div class="flex items-center mt-3 justify-end">
                        <button data-id="{echo_var=id}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Ajouter à l'historique <i class="fa-solid fa-arrow-right ml-3"></i></button>
                    </div>
                </div>
            </div>
        {# endforeach #}
    </div>
</div>
<script src="/myapp/public/js/movie.js"></script>