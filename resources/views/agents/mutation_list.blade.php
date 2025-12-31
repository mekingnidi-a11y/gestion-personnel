<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-indigo-900 uppercase tracking-tighter">Mouvements de Personnel Internes</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-orange-100">
            <div class="bg-orange-50 p-6 border-b border-orange-100">
                <h3 class="text-orange-800 text-sm font-black uppercase">Sélectionner un agent pour une réaffectation</h3>
                <p class="text-orange-600 text-[10px] font-bold uppercase mt-1">Seuls les agents déjà installés en local apparaissent ici</p>
            </div>
            
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase">Agent</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase">Affectation Actuelle</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 uppercase">Poste / Fonction</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($agents as $agent)
                    <tr class="hover:bg-orange-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $agent->nom_complet }}</div>
                            <div class="text-[10px] text-gray-500 font-bold uppercase">Matricule : {{ $agent->matricule ?? 'En attente' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-bold text-indigo-900 uppercase">{{ $agent->affectationActuelle->service->nom ?? 'Direction Générale' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium text-gray-600">{{ $agent->affectationActuelle->fonction ?? 'Agent' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('agents.mutation.create', $agent->id) }}" 
                               class="bg-orange-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-orange-700 shadow-lg shadow-orange-100 transition">
                                Muter l'agent
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic font-bold uppercase text-xs">Aucun agent actif trouvé dans la base locale.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
