<x-guest-layout>
    <div class="flex flex-col lg:flex-row min-h-screen w-full bg-white dark:bg-slate-950 transition-colors duration-300">
        
        <!-- GAUCHE : VISUEL IDENTITÉ VISUELLE METP (Caché sur petit mobile, visible en haut sur tablette) -->
        <div class="hidden lg:flex lg:w-1/3 bg-indigo-950 dark:bg-indigo-1000 p-8 lg:p-12 flex-col justify-center text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-20">
                <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 1440 800">
                    <path fill="#4338ca" d="M0,192L48,176C96,160,192,128,288,144C384,160,480,224,576,218.7C672,213,768,139,864,128C960,117,1056,171,1152,197.3C1248,224,1344,224,1392,224L1440,224L1440,800L1392,800C1344,800,1248,800,1152,800C1056,800,960,800,864,800C768,800,672,800,576,800C480,800,384,800,288,800C192,800,96,800,48,800L0,800Z"></path>
                </svg>
            </div>
            <div class="relative z-10">
                <h2 class="text-3xl lg:text-4xl font-black uppercase leading-tight text-blue-400 italic">
                    Portail RH <br><span class="text-white">METP 2025</span>
                </h2>
                <p class="mt-6 text-indigo-200 text-base lg:text-lg">
                    Espace de gestion décentralisée des carrières et des accès administratifs.
                </p>
            </div>
        </div>

        <!-- DROITE : FORMULAIRES DE CONNEXION ET SIGNALEMENT -->
        <div class="w-full lg:w-2/3 flex items-center justify-center p-4 sm:p-8 bg-gray-50 dark:bg-slate-900 transition-colors duration-300">
            <div class="max-w-md w-full py-8">
                
                <!-- BLOC CONNEXION PRINCIPALE -->
                <div class="bg-white dark:bg-slate-800 p-6 sm:p-10 rounded-[2.5rem] lg:rounded-[3rem] shadow-2xl border border-gray-100 dark:border-slate-700 mb-6 transition-colors">
                    <div class="mb-8 text-center">
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tight italic">Connexion</h2>
                        <p class="text-slate-400 dark:text-slate-500 text-[10px] font-bold uppercase mt-1 tracking-widest italic">Accédez à votre espace sécurisé</p>
                    </div>

                    <!-- MESSAGE DE STATUT -->
                    @if (session('status'))
                        <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 text-amber-800 dark:text-amber-400 text-[10px] font-black uppercase rounded-r-2xl shadow-sm italic animate-pulse">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="leading-tight">{{ session('status') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- AFFICHAGE DES ERREURS -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-400 text-[11px] font-black uppercase rounded-r-xl">
                            <ul class="list-none">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5 sm:space-y-6">
                        @csrf

                        <!-- Identifiant Système -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1 ml-1 tracking-[0.2em]">Identifiant</label>
                            <input type="text" name="username" value="{{ old('username') }}" required autofocus
                                   class="w-full px-5 py-4 bg-gray-50 dark:bg-slate-900 border-transparent dark:border-slate-700 focus:bg-white dark:focus:bg-slate-800 dark:text-white focus:ring-4 focus:ring-blue-500/5 rounded-2xl transition-all font-bold text-slate-700 shadow-inner" 
                                   placeholder="Ex: j.dupont">
                        </div>

                        <!-- Mot de passe personnel -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1 ml-1 tracking-[0.2em]">Mot de passe</label>
                            <input type="password" name="password"
                                   class="w-full px-5 py-4 bg-gray-50 dark:bg-slate-900 border-transparent dark:border-slate-700 focus:bg-white dark:focus:bg-slate-800 dark:text-white focus:ring-4 focus:ring-blue-500/5 rounded-2xl transition-all font-bold text-slate-700 shadow-inner" 
                                   placeholder="••••••••">
                            <div class="mt-3 p-3 bg-indigo-50/50 dark:bg-indigo-900/20 rounded-xl border border-indigo-100 dark:border-indigo-900/30">
                                <p class="text-[9px] text-indigo-600 dark:text-indigo-400 font-bold leading-tight italic">
                                    * Première connexion ? Laissez le mot de passe vide si votre compte vient d'être validé.
                                </p>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-4 sm:py-5 bg-indigo-600 hover:bg-blue-700 text-white rounded-2xl font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 dark:shadow-none transition-all active:scale-95">
                                Entrer dans le portail
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 text-center">
                        <a href="{{ route('register') }}" class="text-xs font-black text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 uppercase tracking-widest transition-colors border-b-2 border-transparent hover:border-indigo-600 pb-1 italic">
                            Demander un accès (Inscription)
                        </a>
                    </div>
                </div>

                <!-- BLOC SIGNALEMENT OUBLI / ACCÈS BLOQUÉ -->
                <div class="bg-amber-50 dark:bg-amber-900/10 p-5 sm:p-6 rounded-[2.5rem] border border-amber-100 dark:border-amber-900/30 shadow-sm transition-colors">
                    <form method="POST" action="{{ route('password.request.admin') }}">
                        @csrf
                        <div class="mb-4 text-center">
                            <p class="text-[10px] font-black text-amber-700 dark:text-amber-500 uppercase tracking-widest">
                                Problème d'accès ?
                            </p>
                            <p class="text-[9px] text-amber-500 dark:text-amber-600 font-bold uppercase mt-1 italic leading-tight">
                                Signalez-le ici pour notifier l'administrateur
                            </p>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-2">
                            <input type="text" name="username" placeholder="Identifiant" required 
                                   class="flex-1 px-4 py-3 bg-white dark:bg-slate-900 border-amber-100 dark:border-amber-900/30 dark:text-white focus:ring-4 focus:ring-amber-500/10 rounded-xl text-xs font-black text-slate-700 shadow-sm uppercase">
                            <button type="submit" class="px-5 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-md shadow-amber-200 dark:shadow-none">
                                Signaler
                            </button>
                        </div>
                    </form>
                </div>

                <p class="mt-8 text-center text-[9px] font-bold text-slate-300 dark:text-slate-600 uppercase tracking-[0.4em]">METP - Système de Gestion Décentralisée</p>

            </div>
        </div>
    </div>
</x-guest-layout>
