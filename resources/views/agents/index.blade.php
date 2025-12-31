<x-app-layout>
    @section('header-title', 'Gestion des Agents')

    <div class="max-w-7xl mx-auto py-8 px-4">
        <!-- BARRE DE RECHERCHE RÉDUITE ET BOUTON (+) -->
        <div class="flex flex-row justify-between items-center mb-6 gap-4">
            <!-- Recherche compacte (w-1/4) -->
            <form action="{{ route('agents.index') }}" method="GET" class="w-full md:w-1/4 flex">
                <input type="text" name="search" value="{{ $search }}" placeholder="Recherche rapide..." 
                       class="w-full border-gray-300 rounded-l-2xl focus:ring-indigo-500 focus:border-indigo-500 text-xs py-2">
                <button type="submit" class="bg-indigo-600 text-white px-3 rounded-r-2xl hover:bg-indigo-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </form>

            @if(Auth::user()->role === 'admin_rh')
                <!-- BOUTON (+) SANS TEXTE -->
                <a href="{{ route('agents.create') }}" 
                   class="h-10 w-10 bg-green-600 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-green-700 hover:scale-110 transition-all transform shrink-0" 
                   title="Nouveau Recrutement">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v12m6-6H6"/>
                    </svg>
                </a>
            @endif
        </div>

        <!-- TABLEAU AVEC DÉFILEMENT VERTICAL -->
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <div class="overflow-y-auto" style="max-height: 600px;">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 z-10 bg-gray-50 shadow-sm">
                        <tr class="border-b border-gray-100">
                            <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase">Agent</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase">Matricule</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase">Structure Actuelle</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase">Grade / Cat</th>
                            <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($agents as $agent)
                        <tr class="hover:bg-indigo-50/50 transition">
                            <td class="px-6 py-3">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold mr-3 text-[10px]">
                                        {{ substr($agent->nom, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 leading-tight">{{ $agent->nom_complet }}</div>
                                        <div class="text-[9px] text-gray-400 uppercase tracking-tighter">{{ $agent->sexe == 'M' ? 'Masculin' : 'Féminin' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3">
                                @if($agent->matricule)
                                    <span class="px-2 py-0.5 bg-green-50 text-green-700 rounded text-[10px] font-bold border border-green-200">{{ $agent->matricule }}</span>
                                @else
                                    <span class="text-[9px] italic text-gray-400">Non attribué</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-[11px] text-gray-600 font-medium">
                                {{ $agent->affectationActuelle->direction->nom ?? 'Non affecté' }}
                            </td>
                            <td class="px-6 py-3">
                                <div class="text-[11px] text-gray-900 font-bold">{{ $agent->situationActuelle->grade ?? 'N/A' }}</div>
                                <div class="text-[9px] text-gray-500">Cat: {{ $agent->situationActuelle->categorie ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex justify-center gap-1">
                                    <a href="{{ route('agents.show', $agent->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Voir">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <!-- Bouton Modifier -->
<a href="{{ route('agents.edit', $agent->id) }}" class="p-1.5 text-yellow-600 hover:bg-yellow-50 rounded-lg transition" title="Modifier">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
</a>

                                    @if(Auth::user()->role === 'admin_rh')
                                        <button onclick="openAffectationModal('{{ $agent->id }}', '{{ $agent->nom_complet }}')" class="p-1.5 text-purple-600 hover:bg-purple-50 rounded-lg transition" title="Affecter">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        </button>
                                        @if(!$agent->matricule)
                                        <button onclick="openMatriculeModal('{{ $agent->id }}', '{{ $agent->nom_complet }}')" class="p-1.5 text-orange-600 hover:bg-orange-50 rounded-lg transition" title="Immatriculer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>

                                        
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic text-xs">Aucun agent trouvé.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $agents->links() }}
        </div>
    </div>

    <!-- MODALE AFFECTATION -->
    <div id="modalAffectation" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="bg-indigo-900 p-4 text-white flex justify-between items-center">
                <h3 class="font-black text-[10px] tracking-widest uppercase">Nouvelle Affectation</h3>
                <button onclick="closeAffectationModal()" class="text-white text-xl leading-none">&times;</button>
            </div>
            <form id="formAffectation" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-[9px] font-black text-gray-400 uppercase mb-1">Agent</label>
                    <input type="text" id="modal_agent_name" disabled class="w-full bg-gray-50 border-none rounded-xl font-bold text-gray-700 text-xs py-2">
                </div>
                <div>
                    <label class="block text-[9px] font-black text-gray-400 uppercase mb-1">Direction de destination</label>
                    <select name="direction_id" required class="w-full border-gray-200 rounded-xl focus:ring-indigo-500 text-xs py-2">
                        <option value="">-- Sélectionner --</option>
                        @foreach($directions as $direction)
                            <option value="{{ $direction->id }}">{{ $direction->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] font-black text-gray-400 uppercase mb-1">Référence Acte</label>
                    <input type="text" name="ref_acte_affectation" required class="w-full border-gray-200 rounded-xl text-xs py-2">
                </div>
                <div>
                    <label class="block text-[9px] font-black text-gray-400 uppercase mb-1">Date d'effet</label>
                    <input type="date" name="date_debut" required value="{{ date('Y-m-d') }}" class="w-full border-gray-200 rounded-xl text-xs py-2">
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeAffectationModal()" class="text-[10px] font-bold text-gray-400 uppercase">Annuler</button>
                    <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-xl font-bold uppercase text-[10px] shadow-md hover:bg-indigo-700">Valider</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAffectationModal(id, name) {
            document.getElementById('modal_agent_name').value = name;
            document.getElementById('formAffectation').action = `/agents/${id}/affecter`;
            document.getElementById('modalAffectation').classList.replace('hidden', 'flex');
        }
        function closeAffectationModal() {
            document.getElementById('modalAffectation').classList.replace('flex', 'hidden');
        }
    </script>

    <style>
        .overflow-y-auto::-webkit-scrollbar { width: 5px; }
        .overflow-y-auto::-webkit-scrollbar-track { background: #f8fafc; }
        .overflow-y-auto::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .overflow-y-auto::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
</x-app-layout>
