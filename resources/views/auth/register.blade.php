<x-guest-layout>
    <div class="fixed inset-0 flex h-screen w-full bg-white overflow-hidden">
        
        <!-- GAUCHE : VISUEL IDENTITÉ -->
        <div class="hidden lg:flex lg:w-1/3 bg-indigo-900 p-12 flex-col justify-center text-white relative">
            <div class="absolute inset-0 opacity-20">
                <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 1440 800">
                    <path fill="#4338ca" d="M0,192L48,176C96,160,192,128,288,144C384,160,480,224,576,218.7C672,213,768,139,864,128C960,117,1056,171,1152,197.3C1248,224,1344,224,1392,224L1440,224L1440,800L1392,800C1344,800,1248,800,1152,800C1056,800,960,800,864,800C768,800,672,800,576,800C480,800,384,800,288,800C192,800,96,800,48,800L0,800Z"></path>
                </svg>
            </div>
            <div class="relative z-10">
                <h2 class="text-4xl font-black uppercase italic text-blue-400">Inscription <br><span class="text-white">Agents</span></h2>
                <p class="mt-6 text-indigo-200">Remplissez les informations pour soumettre votre demande à la Direction des Ressources Humaines.</p>
            </div>
        </div>

        <!-- DROITE : FORMULAIRE -->
        <div class="w-full lg:w-2/3 flex items-center justify-center p-8 bg-gray-50 overflow-y-auto">
            <div class="max-w-md w-full">
                
                <div class="bg-white p-10 rounded-[3rem] shadow-2xl border border-gray-100">
                    <div class="mb-8 text-center">
                        <h2 class="text-3xl font-black text-slate-900 uppercase italic">Nouvelle Demande</h2>
                        <p class="text-slate-400 text-[10px] font-bold uppercase mt-1 tracking-widest">En attente de validation admin</p>
                    </div>

                    <!-- Affichage des erreurs -->
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 text-red-700 text-[11px] font-black uppercase rounded-xl border-l-4 border-red-500">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <!-- Identifiant choisi -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Identifiant Souhaité</label>
                            <input type="text" name="username" value="{{ old('username') }}" required autofocus
                                   class="w-full px-5 py-4 bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-blue-500/5 rounded-2xl font-bold text-slate-700 shadow-inner" 
                                   placeholder="Ex: j.dupont">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Email (Optionnel)</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="w-full px-5 py-4 bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-blue-500/5 rounded-2xl font-bold text-slate-700 shadow-inner" 
                                   placeholder="email@domaine.com">
                        </div>

                        <!-- Sélection Direction -->
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">Direction de Rattachement</label>
                            <div class="relative">
                                <select name="direction_id" required
                                        class="w-full px-5 py-4 bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-blue-500/5 rounded-2xl font-bold text-slate-700 shadow-inner appearance-none">
                                    <option value="">-- Choisir une direction --</option>
                                    @foreach($directions as $direction)
                                        {{-- Utilisation de $direction->id (UUID) et $direction->nom (Label) selon votre SQL --}}
                                        <option value="{{ $direction->id }}">
                                            {{ $direction->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black uppercase tracking-[0.2em] shadow-xl shadow-blue-100 transition-all active:scale-95">
                                Envoyer la demande
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 text-center border-t border-gray-100 pt-6">
                        <a href="{{ route('login') }}" class="text-[10px] font-black text-indigo-600 hover:text-blue-700 uppercase tracking-widest transition-colors italic">
                            Retour à la connexion
                        </a>
                    </div>
                </div>

                <p class="mt-8 text-center text-[9px] font-bold text-slate-300 uppercase tracking-[0.4em]">METP - Système de Gestion Décentralisée</p>
            </div>
        </div>
    </div>
</x-guest-layout>
