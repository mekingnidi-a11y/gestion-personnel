<x-app-layout>
    @section('header-title', 'Liste des Mutations Internes')

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 transition-colors duration-300">
        <!-- Barre de recherche Adaptative -->
        <div class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex justify-between items-center transition-colors">
            <form action="{{ route('agents.mutation.index') }}" method="GET" class="flex gap-4 flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Rechercher un agent (Nom, Prénom, Matricule)..." 
                       class="w-full max-w-md border-gray-200 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl shadow-sm focus:ring-orange-500 focus:border-orange-500 text-sm font-medium">
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-xl font-black uppercase text-[10px] shadow-lg shadow-orange-100 dark:shadow-none transition-all">
                    Rechercher
                </button>
            </form>
        </div>

        <!-- Tableau des Mutations -->
        <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-colors">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Agent</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Matricule</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Service Actuel</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($agents as $agent)
                    <tr class="hover:bg-orange-50/30 dark:hover:bg-orange-900/10 transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $agent->nom_complet }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-1 rounded">
                                {{ $agent->matricule ?? 'NON ATTRIBUÉ' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-tight">
                                {{ $agent->affectationActuelle->service->nom ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('agents.mutation.create', $agent->id) }}" 
                               class="inline-block bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 hover:bg-orange-600 dark:hover:bg-orange-600 hover:text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase transition-all shadow-sm">
                                Muter l'agent
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center text-gray-400 dark:text-gray-600 text-xs font-bold uppercase italic tracking-widest">
                            Aucun agent trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 dark:text-gray-400">
            {{ $agents->links() }}
        </div>
    </div>
</x-app-layout>
