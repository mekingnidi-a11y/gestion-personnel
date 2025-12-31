<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">
                    @if(Auth::user()->role === 'admin_rh')
                        Tableau de Bord - Administration Centrale (RH)
                    @elseif(Auth::user()->role === 'admin_direction_generale')
                        Tableau de Bord - Direction Générale
                    @else
                        Tableau de Bord - Direction Locale
                    @endif
                </h1>
                <p class="text-gray-600">Bienvenue, {{ Auth::user()->username }} ({{ Auth::user()->code_direction }})</p>
            </div>

            <!-- Grille des statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-indigo-600">
                    <div class="text-sm font-bold text-indigo-600 uppercase">Total Agents</div>
                    <div class="text-4xl font-black text-gray-800">{{ $stats['total_agents'] }}</div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-orange-500">
                    <div class="text-sm font-bold text-orange-500 uppercase">En attente d'installation</div>
                    <div class="text-4xl font-black text-gray-800">{{ $stats['en_attente'] }}</div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-red-500">
                    <div class="text-sm font-bold text-red-500 uppercase">Sans Matricule</div>
                    <div class="text-4xl font-black text-gray-800">{{ $stats['sans_matricule'] }}</div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
