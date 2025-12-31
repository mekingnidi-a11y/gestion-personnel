<x-app-layout>
    @section('header-title', 'Détails du Bureau')

    <div class="max-w-4xl mx-auto py-8 px-4 transition-colors duration-300">
        <!-- CARTE PRINCIPALE -->
        <div class="bg-white dark:bg-gray-800 rounded-[3rem] shadow-2xl p-10 border border-gray-100 dark:border-gray-700">
            
            <div class="flex flex-col sm:flex-row justify-between items-start mb-8 gap-4">
                <div>
                    <span class="text-[10px] font-black bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 px-4 py-1.5 rounded-full uppercase tracking-widest">
                        Code Bureau : {{ $bureau->code }}
                    </span>
                    <h1 class="text-3xl font-black text-slate-800 dark:text-white uppercase mt-4 tracking-tighter">
                        {{ $bureau->nom }}
                    </h1>
                </div>
                <a href="{{ route('bureaux.index') }}" class="text-slate-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 font-black text-[10px] uppercase tracking-widest transition-colors">
                    &larr; Retour au répertoire
                </a>
            </div>

            <!-- GRILLE D'INFORMATIONS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <div class="p-6 bg-slate-50 dark:bg-gray-900/50 rounded-3xl border border-transparent dark:border-gray-700/50 transition-colors">
                    <h3 class="text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase mb-2 tracking-widest">
                        Service de rattachement
                    </h3>
                    <p class="font-bold text-slate-700 dark:text-gray-300 uppercase text-sm leading-tight">
                        {{ $bureau->service->nom ?? 'N/A' }}
                    </p>
                </div>
                <div class="p-6 bg-slate-50 dark:bg-gray-900/50 rounded-3xl border border-transparent dark:border-gray-700/50 transition-colors">
                    <h3 class="text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase mb-2 tracking-widest">
                        Localisation physique
                    </h3>
                    <p class="font-bold text-slate-700 dark:text-gray-300 text-sm italic">
                        {{ $bureau->localisation ?? 'Non spécifiée' }}
                    </p>
                </div>
            </div>

            <!-- SECTION CAPACITÉ -->
            <div class="p-8 border border-gray-100 dark:border-gray-700 rounded-[2rem] bg-white dark:bg-gray-800/50 flex items-center gap-4 transition-colors">
                <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m12 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase mb-0.5 tracking-widest">
                        Capacité d'accueil
                    </h3>
                    <p class="text-slate-800 dark:text-gray-200 font-black text-lg">
                        {{ $bureau->capacite ?? 0 }} <span class="text-sm font-bold text-slate-400 dark:text-gray-500">poste(s) de travail</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
