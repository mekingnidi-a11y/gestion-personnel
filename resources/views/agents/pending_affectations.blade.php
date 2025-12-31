<x-app-layout>
    @section('header-title', 'Arrivées & Installations')

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 transition-colors duration-300">
        <!-- CONTENEUR PRINCIPAL -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-3xl overflow-hidden border border-amber-100 dark:border-amber-900/30">
            
            <!-- BANDEAU D'INFORMATION AMBRE -->
            <div class="bg-amber-50 dark:bg-amber-900/20 p-4 border-b border-amber-100 dark:border-amber-900/40">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-amber-700 dark:text-amber-400 text-[10px] font-black uppercase tracking-wider leading-relaxed">
                        Liste des agents orientés par la DGARH vers votre direction. Veuillez procéder à leur installation locale.
                    </p>
                </div>
            </div>
            
            <!-- TABLEAU DES AGENTS -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Agent</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Date Recrutement</th>
                            <th class="px-6 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Direction Cible</th>
                            <th class="px-6 py-4 text-right text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($agents as $agent)
                        <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/20 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900 dark:text-white leading-tight">
                                    {{ $agent->nom_complet }}
                                </div>
                                <div class="text-[10px] text-gray-500 dark:text-gray-400 italic mt-0.5 uppercase tracking-tighter">
                                    {{ $agent->sexe == 'M' ? 'Né le' : 'Née le' }} {{ \Carbon\Carbon::parse($agent->date_naissance)->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-gray-600 dark:text-gray-300">
                                {{ \Carbon\Carbon::parse($agent->date_recrutement)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 text-[9px] font-black rounded-lg border border-indigo-200 dark:border-indigo-800 uppercase">
                                    {{ $agent->affectationActuelle->direction->nom ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('agents.prise-service.create', $agent->id) }}" 
                                   class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase shadow-lg shadow-indigo-100 dark:shadow-none transition-all active:scale-95">
                                    Installer l'agent
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-200 dark:text-gray-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <p class="text-gray-400 dark:text-gray-600 italic text-xs font-bold uppercase tracking-widest">
                                        Aucune affectation en attente pour le moment.
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
