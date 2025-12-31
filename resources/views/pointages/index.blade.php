<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 transition-colors duration-300" x-data="{ tab: 'pointage' }">
        
        <!-- Barre d'onglets Adaptative -->
        <div class="flex flex-wrap gap-2 md:space-x-4 mb-6 bg-white dark:bg-gray-800 p-2 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            
            <!-- Bouton Pointage -->
            <button @click="tab = 'pointage'" 
                :class="tab == 'pointage' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200 dark:shadow-none' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'" 
                class="px-6 py-2.5 rounded-xl text-[10px] md:text-xs font-black uppercase tracking-widest transition-all duration-200">
                Pointage Journalier
            </button>

            <!-- Bouton Absences -->
            <button @click="tab = 'absences'" 
                :class="tab == 'absences' ? 'bg-orange-600 text-white shadow-lg shadow-orange-200 dark:shadow-none' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'" 
                class="px-6 py-2.5 rounded-xl text-[10px] md:text-xs font-black uppercase tracking-widest transition-all duration-200">
                Absences & Congés
            </button>

            <!-- Bouton États -->
            <button @click="tab = 'etats'" 
                :class="tab == 'etats' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200 dark:shadow-none' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'" 
                class="px-6 py-2.5 rounded-xl text-[10px] md:text-xs font-black uppercase tracking-widest transition-all duration-200">
                États & Rapports
            </button>
        </div>

        <!-- Zones de Contenu -->
        <div class="animate-fadeIn">
            <!-- Onglet 1 : Pointage par Recherche -->
            <div x-show="tab === 'pointage'" x-cloak class="space-y-6">
                @include('pointages.partials.journalier')
            </div>

            <!-- Onglet 2 : Absences Justifiées -->
            <div x-show="tab === 'absences'" x-cloak class="space-y-6">
                @include('pointages.partials.absences')
            </div>

            <!-- Onglet 3 : États et Statistiques -->
            <div x-show="tab === 'etats'" x-cloak class="space-y-6">
                @include('pointages.partials.etats')
            </div>
        </div>
    </div>

    {{-- Style pour éviter le clignotement AlpineJS au chargement --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
