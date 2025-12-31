<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ tab: 'pointage' }">
        <!-- Barre d'onglets -->
        <div class="flex space-x-4 mb-6 bg-white p-2 rounded-2xl shadow-sm border border-gray-100">
            <button @click="tab = 'pointage'" :class="tab == 'pointage' ? 'bg-indigo-600 text-white' : 'text-gray-500 hover:bg-gray-50'" class="px-6 py-2 rounded-xl text-xs font-black uppercase transition">Pointage Journalier</button>
            <button @click="tab = 'absences'" :class="tab == 'absences' ? 'bg-orange-600 text-white' : 'text-gray-500 hover:bg-gray-50'" class="px-6 py-2 rounded-xl text-xs font-black uppercase transition">Absences & Congés</button>
            <button @click="tab = 'etats'" :class="tab == 'etats' ? 'bg-emerald-600 text-white' : 'text-gray-500 hover:bg-gray-50'" class="px-6 py-2 rounded-xl text-xs font-black uppercase transition">États & Rapports</button>
        </div>

        <!-- Onglet 1 : Pointage par Recherche -->
        <div x-show="tab === 'pointage'" class="space-y-6">
            @include('pointages.partials.journalier')
        </div>

        <!-- Onglet 2 : Absences Justifiées -->
        <div x-show="tab === 'absences'" class="space-y-6">
            @include('pointages.partials.absences')
        </div>

        <!-- Onglet 3 : États et Statistiques -->
        <div x-show="tab === 'etats'" class="space-y-6">
            @include('pointages.partials.etats')
        </div>
    </div>
</x-app-layout>
