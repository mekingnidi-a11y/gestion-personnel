<x-app-layout>
    @section('header-title', 'Liste des Mutations Internes')

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Barre de recherche -->
        <div class="mb-6 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
            <form action="{{ route('agents.mutation.index') }}" method="GET" class="flex gap-4 flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Rechercher un agent (Nom, Prénom, Matricule)..." 
                       class="w-full max-w-md border-gray-200 rounded-xl shadow-sm focus:ring-orange-500 focus:border-orange-500 text-sm">
                <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded-xl font-black uppercase text-[10px]">
                    Rechercher
                </button>
            </form>
        </div>

        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase">Agent</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase">Matricule</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase">Service Actuel</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($agents as $agent)
                    <tr class="hover:bg-orange-50/30 transition">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $agent->nom_complet }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-mono font-bold text-indigo-600">{{ $agent->matricule ?? 'NON ATTRIBUÉ' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[10px] font-black text-gray-600 uppercase">{{ $agent->affectationActuelle->service->nom ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('agents.mutation.create', $agent->id) }}" 
                               class="inline-block bg-orange-100 text-orange-700 hover:bg-orange-600 hover:text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase transition">
                                Muter l'agent
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 text-xs font-bold uppercase">Aucun agent trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $agents->links() }}
        </div>
    </div>
</x-app-layout>
