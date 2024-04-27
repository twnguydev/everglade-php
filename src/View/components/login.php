<div class="bg-black text-white flex min-h-screen flex-col items-center pt-16 sm:justify-center sm:pt-0">
    <div class="text-foreground m-8 font-semibold text-center text-4xl md:text-5xl lg:text-6xl tracking-tighter mx-auto flex items-center gap-2 w-full max-w-2xl">
        Rejoignez dès maintenant la plus large communauté d'échange cinématographique.
    </div>
    <div class="relative mt-12 w-full max-w-lg sm:mt-10">
        <div class="relative -mb-px h-px w-full bg-gradient-to-r from-transparent via-sky-300 to-transparent" bis_skin_checked="1"></div>
        <div class="mx-5 border dark:border-b-white/50 dark:border-t-white/50 border-b-white/20 sm:border-t-white/20 shadow-[20px_0_20px_20px] shadow-slate-500/10 dark:shadow-white/20 rounded-lg border-white/20 border-l-white/20 border-r-white/20 sm:shadow-sm lg:rounded-xl lg:shadow-none">
            <div class="flex flex-col p-6">
                <h3 class="text-xl font-semibold leading-6 tracking-tighter">Connexion</h3>
                <p class="mt-1.5 text-sm font-medium text-white/50">Welcome back, entrez vos identifiants pour continuer.
                </p>
            </div>
            <div class="p-6 pt-0">
                <form method="post" action="javascript:void(0);">
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert" id="error-box">
                        <span class="font-medium">Alerte danger!</span> Changez quelques éléments et réessayez de soumettre.<br>
                        <span class="text-xs" id="error-message"></span>
                    </div>
                    <div>
                        <div>
                            <div class="group relative rounded-lg border focus-within:border-sky-200 px-3 pb-1.5 pt-2.5 duration-200 focus-within:ring focus-within:ring-sky-300/30">
                                <div class="flex justify-between">
                                    <label class="text-xs font-medium text-muted-foreground group-focus-within:text-white text-gray-400">Pseudonyme ou e-mail</label>
                                </div>
                                <input type="text" name="usernameOrEmail" id="usernameOrEmail" placeholder="John Doe" autocomplete="off" class="block w-full border-0 bg-transparent p-0 text-sm file:my-1 file:rounded-full file:border-0 file:bg-accent file:px-4 file:py-2 file:font-medium placeholder:text-muted-foreground/90 focus:outline-none focus:ring-0 sm:leading-7 text-foreground">
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div>
                            <div class="group relative rounded-lg border focus-within:border-sky-200 px-3 pb-1.5 pt-2.5 duration-200 focus-within:ring focus-within:ring-sky-300/30">
                                <div class="flex justify-between">
                                    <label class="text-xs font-medium text-muted-foreground group-focus-within:text-white text-gray-400">Mot de passe</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="password" name="password" id="password" class="block w-full border-0 bg-transparent p-0 text-sm file:my-1 placeholder:text-muted-foreground/90 focus:outline-none focus:ring-0 focus:ring-teal-500 sm:leading-7 text-foreground">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="remember" class="outline-none focus:outline focus:outline-sky-300">
                            <span class="text-xs">Se souvenir de moi</span>
                        </label>
                        <a class="text-sm font-medium text-foreground underline" href="/forgot-password">Mot de passe oublié ?</a>
                    </div>
                    <div class="mt-4 flex items-center justify-end gap-x-2">
                        <a class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:ring hover:ring-white h-10 px-4 py-2 duration-200" href="/auth/signup">Inscription</a>
                        <button id="submit-btn" class="font-semibold hover:bg-black hover:text-white hover:ring hover:ring-white transition duration-300 inline-flex items-center justify-center rounded-md text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-white text-black h-10 px-4 py-2" type="submit">Je me connecte</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="/myapp/public/js/login.js"></script>