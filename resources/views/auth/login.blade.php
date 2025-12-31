<x-guest-layout>
    <div class="fixed inset-0 flex h-screen w-full bg-white overflow-hidden">
        
        <!-- GAUCHE : VISUEL IDENTITÉ VISUELLE METP -->
        <div class="hidden lg:flex lg:w-1/3 bg-indigo-950 p-12 flex-col justify-center text-white relative">
            <div class="absolute inset-0 opacity-20">
                <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 1440 800">
                    <path fill="#4338ca" d="M0,192L48,176C96,160,192,128,288,144C384,160,480,224,576,218.7C672,213,768,139,864,128C960,117,1056,171,1152,197.3C1248,224,1344,224,1392,224L1440,224L1440,800L1392,800C1344,800,1248,800,1152,800C1056,800,960,800,864,800C768,800,672,800,576,800C480,800,384,800,288,800C192,800,96,800,48,800L0,800Z"></path>
                </svg>
            </div>
            <div class="relative z-10">
                <h2 class="text-4xl font-black uppercase leading-tight text-blue-400 italic">Portail RH <br><span class="text-white">METP 2025</span></h2>
                <p class="mt-6 text-indigo-200 text-lg">Espace de gestion décentralisée des carrières et des accès administratifs.</p>
            </div>
        </div>

        <!-- DROITE : FORMULAIRES DE CONNEXION ET SIGNALEMENT -->
        <div class="w-full lg:w-2/3 flex items-center justify-center p-8 bg-gray-50 overflow-y-auto">
            <div class="max-w-md w-full">
                
                <!-- BLOC CONNEXION PRINCIPALE -->
                <div class="bg-white p-10 rounded-[3rem] shadow-2xl border border-gray-100 mb-6">
                    <div class="mb-8 text-center">
                        <h2 class="text-3xl font-black text-slate-900 uppercase tracking-tight italic">Connexion</h2>
                        <p class="text-slate-400 text-[10px] font-bold uppercase mt-1 tracking-widest italic">Accédez à votre espace sécurisé</p>
                    </div>

                    <!-- MESSAGE DE STATUT (Validation en cours ou Signalement envoyé) -->
                    @if (session('status'))
                        <div class="mb-6 p-5 bg-amber-50 border-l-4 border-amber-500 text-amber-800 text-[10px] font-black uppercase rounded-r-2xl shadow-sm italic animate-pulse">
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
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-[11px] font-black uppercase rounded-r-xl">
                            <ul class="list-none">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Identifiant Système -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1 tracking-[0.2em]">Identifiant (Username)</label>
                            <input type="text" name="username" value="{{ old('username') }}" required autofocus
                                   class="w-full px-5 py-4 bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-blue-500/5 rounded-2xl transition-all font-bold text-slate-700 shadow-inner" 
                                   placeholder="Ex: j.dupont">
                        </div>

                        <!-- Mot de passe personnel -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1 tracking-[0.2em]">Mot de passe</label>
                            <input type="password" name="password"
                                   class="w-full px-5 py-4 bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-blue-500/5 rounded-2xl transition-all font-bold text-slate-700 shadow-inner" 
                                   placeholder="••••••••">
                            <div class="mt-3 p-3 bg-indigo-50/50 rounded-xl border border-indigo-100">
                                <p class="text-[9px] text-indigo-600 font-bold leading-tight italic">
                                    * Première connexion ? Laissez le mot de passe vide si votre compte vient d'être validé.
                                </p>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-5 bg-indigo-600 hover:bg-blue-700 text-white rounded-2xl font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 transition-all active:scale-95">
                                Entrer dans le portail
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 text-center">
                        <a href="{{ route('register') }}" class="text-xs font-black text-slate-400 hover:text-indigo-600 uppercase tracking-widest transition-colors border-b-2 border-transparent hover:border-indigo-600 pb-1 italic">
                            Demander un accès (Inscription)
                        </a>
                    </div>
                </div>

                <!-- BLOC SIGNALEMENT OUBLI / ACCÈS BLOQUÉ -->
                <div class="bg-amber-50 p-6 rounded-[2.5rem] border border-amber-100 shadow-sm">
                    <form method="POST" action="{{ route('password.request.admin') }}">
                        @csrf
                        <div class="mb-4 text-center">
                            <p class="text-[10px] font-black text-amber-700 uppercase tracking-widest">
                                Problème d'accès ou Identifiant oublié ?
                            </p>
                            <p class="text-[9px] text-amber-500 font-bold uppercase mt-1 italic leading-tight">
                                Signalez-le ici pour notifier l'administrateur RH
                            </p>
                        </div>
                        
                        <div class="flex gap-2">
                            <input type="text" name="username" placeholder="Saisir votre identifiant" required 
                                   class="flex-1 px-4 py-3 bg-white border-amber-100 focus:ring-4 focus:ring-amber-500/10 rounded-xl text-xs font-black text-slate-700 shadow-sm uppercase">
                            <button type="submit" class="px-5 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-md shadow-amber-200">
                                Signaler
                            </button>
                        </div>
                    </form>
                </div>

                <p class="mt-8 text-center text-[9px] font-bold text-slate-300 uppercase tracking-[0.4em]">METP - Système de Gestion Décentralisée</p>

            </div>
        </div>
    </div>
</x-guest-layout>
