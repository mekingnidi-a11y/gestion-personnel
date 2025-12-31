<x-app-layout>
    @section('header-title', 'Mouvements de Personnel Internes')

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 transition-colors duration-300">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-3xl overflow-hidden border border-orange-100 dark:border-orange-900/30">
            
            <!-- HEADER DE SECTION (ORANGE) -->
            <div class="bg-orange-50 dark:bg-orange-900/20 p-6 border-b border-orange-100 dark:border-orange-900/40">
                <h3 class="text-orange-800 dark:text-orange-400 text-sm font-black uppercase tracking-tight flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    Sélectionner un agent pour une réaffectation
                </h3>
                <p class="text-orange-600 dark:text-orange-500 text-[10px] font-bold uppercase mt-1 tracking-widest">
                    Seuls les agents déjà installés en local apparaissent ici
                </p>
            </div>
            
            <!-- TABLEAU DES AGENTS -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Agent</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Affectation Actuelle</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Poste / Fonction</th>
                            <th class="px-6 py-4 text-right text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($agents as $agent)
                        <tr class="hover:bg-orange-50/50 dark:hover:bg-orange-900/10 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $agent->nom_complet }}</div>
                                <div class="text-[10px] text-gray-500 dark:text-gray-400 font-black uppercase mt-0.5 tracking-tighter">
                                    Matricule : <span class="text-indigo-600 dark:text-indigo-400">{{ $agent->matricule ?? 'En attente' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-[11px] font-black text-indigo-900 dark:text-indigo-300 uppercase tracking-tight italic">
                                    {{ $agent->affectationActuelle->service->nom ?? 'Direction Générale' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-400 tracking-tight">
                                    {{ $agent->affectationActuelle->fonction ?? 'Agent' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('agents.mutation.create', $agent->id) }}" 
                                   class="inline-block bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase shadow-lg shadow-orange-100 dark:shadow-none transition-all active:scale-95 tracking-widest">
                                    Muter l'agent
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-200 dark:text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <p class="text-gray-400 dark:text-gray-600 italic font-black uppercase text-[10px] tracking-widest">
                                        Aucun agent actif trouvé dans la base locale.
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
