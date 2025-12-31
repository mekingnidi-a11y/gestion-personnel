<div class="bg-white dark:bg-gray-800 shadow-xl rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700 p-8 text-center transition-colors duration-300">
    <div class="py-12">
        <!-- Icône avec cercle de fond subtil en mode sombre -->
        <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>

        <h3 class="text-emerald-900 dark:text-emerald-400 font-black uppercase text-sm tracking-widest">
            États & Rapports
        </h3>
        
        <div class="flex items-center justify-center mt-4 space-x-2">
            <!-- Petit spinner de chargement pour le style -->
            <svg class="animate-spin h-4 w-4 text-emerald-500" xmlns="www.w3.org" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-400 dark:text-gray-500 text-[10px] uppercase font-black tracking-tighter">
                Statistiques de présences en cours de calcul...
            </p>
        </div>

        <!-- Bouton d'action fictif pour embellir (optionnel) -->
        <div class="mt-8">
            <button disabled class="px-8 py-3 bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 rounded-xl text-[9px] font-black uppercase tracking-widest cursor-not-allowed">
                Générer l'export PDF
            </button>
        </div>
    </div>
</div>
