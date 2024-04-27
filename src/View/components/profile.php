<div class="bg-black text-white flex min-h-screen flex-col items-center pt-16 sm:justify-center sm:pt-0">
    <div class="h-screen bg-gray-200 w-full dark:bg-gray-800 flex flex-col items-center justify-center">
        <div class="container mb-16 lg:w-2/6 xl:w-2/7 sm:w-full md:w-2/3 bg-white shadow-lg transform duration-200 easy-in-out">
            <div class="h-32 overflow-hidden">
                <img class="w-full" src="https://images.unsplash.com/photo-1605379399642-870262d3d051?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=2000&q=80" alt="" />
            </div>
            <div class="flex justify-center px-5 -mt-12">
                <img class="h-32 w-32 bg-white p-2 rounded-full" src="/myapp/public/img/profile-img.webp" alt="" />
            </div>
            <div class="text-black">
                <div class="text-center px-14">
                    <h2 class="text-gray-800 text-3xl font-bold">{{ firstname }} {{ lastname }}</h2>
                    <a class="text-gray-400 mt-2 hover:text-blue-500" href="" target="BLANK()">@{{ username }}</a>
                    <p class="mt-2 text-gray-500 text-sm">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, </p>
                </div>
                <hr class="mt-6" />
                {# if (var=access === true) #}
                <div class="flex bg-gray-50">
                    <div data-modal-target="authentication-modal" data-modal-toggle="authentication-modal" class="text-center w-1/2 p-4 hover:bg-gray-100 cursor-pointer">
                        <p><span class="font-semibold">Modifier le profil</span></p>
                    </div>
                    <div class="border"></div>
                    <div class="text-center w-1/2 p-4 hover:bg-gray-100 cursor-pointer">
                        <a href="/myapp/auth/logout">
                            <p><span class="font-semibold">Se déconnecter</span></p>
                        </a>
                    </div>
                </div>
                {# else #}
                {# endif #}
            </div>
        </div>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Nom du film
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Genre
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Directeur
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date de sortie
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date de visionnage
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {# foreach (history as key => value) #}
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {echo_var=title}
                            </th>
                            <td class="px-6 py-4">
                                {echo_var=genre}
                            </td>
                            <td class="px-6 py-4">
                                {echo_var=director}
                            </td>
                            <td class="px-6 py-4">
                                {echo_var=release_date}
                            </td>
                            <td class="px-6 py-4">
                                {echo_var=date}
                            </td>
                        </tr>
                    {# endforeach #}
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="authentication-modal" tabindex="-1" aria-hidden="true" class="hidden flex bg-gray-600 bg-opacity-50 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Modifier mon profil
                </h3>
                <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="authentication-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
            <div class="p-4 md:p-5">
                <form class="space-y-4" method="post" action="javascript:void(0);">
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert" id="error-box">
                        <span class="font-medium">Alerte danger!</span> Changez quelques éléments et réessayez de soumettre.<br>
                        <span class="text-xs" id="error-message"></span>
                    </div>
                    <div>
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pseudo</label>
                        <input type="text" name="username" id="username" value="{{ username }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Adresse e-mail</label>
                        <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" value="{{ email }}" />
                    </div>
                    <div>
                        <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Numéro de téléphone</label>
                        <input type="phone" name="phone" id="phone" value="{{ phone }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mot de passe actuel</label>
                        <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                    </div>
                    <div>
                        <label for="confirmPassword" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nouveau mot de passe</label>
                        <input type="password" name="confirmPassword" id="confirmPassword" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                    </div>
                    <button type="submit" id="updateAccount" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Modifier mes informations</button>
                    <button type="submit" id="deleteAccount" class="w-full text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm mt-0 px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Supprimer mon compte</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="/myapp/public/js/modal.js"></script>
<script src="/myapp/public/js/profile.js"></script>