<div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{ 
    selectedAgentId: '', 
    selectedAgentName: '', 
    hasArrivee: false, 
    hasDepart: false,
    search: '' 
}">
    
    <!-- GAUCHE : TABLEAU GLOBAL (2/3) -->
    <div class="lg:col-span-2 bg-white shadow-xl rounded-3xl overflow-hidden border border-gray-100">
        <div class="p-6 bg-gray-50 border-b flex justify-between items-center">
            <input type="date" id="date_picker" value="{{ $date }}" onchange="window.location.href='?date='+this.value"
                   class="border-gray-200 rounded-xl text-xs font-bold text-indigo-900">
            
            <input type="text" x-model="search" placeholder="Rechercher dans le tableau..." 
                   class="border-gray-200 rounded-xl text-xs py-2 w-64">
        </div>

        <div class="overflow-y-auto max-h-[600px]">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-indigo-50 sticky top-0">
                    <tr class="text-[9px] font-black text-indigo-900 uppercase">
                        <th class="px-4 py-3 text-left">Agent</th>
                        <th class="px-4 py-3 text-center">Arrivée</th>
                        <th class="px-4 py-3 text-center">Départ</th>
                        <th class="px-4 py-3 text-center">Observation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($agents as $agent)
                    @php $p = $agent->pointages->first(); @endphp
                    <tr class="cursor-pointer hover:bg-indigo-50 transition" 
                        x-show="search === '' || '{{ strtolower($agent->nom_complet) }}'.includes(search.toLowerCase())"
                        @click="
                            selectedAgentId = '{{ $agent->id }}'; 
                            selectedAgentName = '{{ $agent->nom_complet }}';
                            hasArrivee = {{ $p && $p->heure_arrivee ? 'true' : 'false' }};
                            hasDepart = {{ $p && $p->heure_depart ? 'true' : 'false' }};
                        ">
                        <td class="px-4 py-4 text-xs font-bold text-gray-900">{{ $agent->nom_complet }}</td>
                        <td class="px-4 py-3 text-center font-mono text-xs text-indigo-600">
                            {{ $p && $p->heure_arrivee ? \Carbon\Carbon::parse($p->heure_arrivee)->format('H:i') : '--:--' }}
                        </td>
                        <td class="px-4 py-3 text-center font-mono text-xs text-orange-600">
                            {{ $p && $p->heure_depart ? \Carbon\Carbon::parse($p->heure_depart)->format('H:i') : '--:--' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if(!$p)
                                <span class="text-[8px] font-black text-red-500 uppercase">Absent</span>
                            @elseif($p->heure_arrivee > '08:00:00')
                                <span class="text-[8px] font-black text-amber-600 uppercase">Retard</span>
                            @else
                                <span class="text-[8px] font-black text-green-600 uppercase">Ponctuel</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- DROITE : FORMULAIRE DE SAISIE (1/3) -->
    <div class="bg-white shadow-xl rounded-3xl p-6 border border-gray-100 h-fit sticky top-8">
        <h3 class="text-[10px] font-black uppercase text-gray-400 mb-6 tracking-widest">Saisie du pointage</h3>
        
        <form action="{{ route('pointages.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="agent_id" :value="selectedAgentId">
            <input type="hidden" name="date_pointage" value="{{ $date }}">

            <div>
                <label class="block text-[9px] font-black text-gray-400 uppercase mb-1">Agent Sélectionné</label>
                <input type="text" :value="selectedAgentName" readonly 
                       class="w-full bg-gray-50 border-none rounded-xl font-bold text-indigo-900 text-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[9px] font-black text-gray-400 uppercase mb-1">Heure Arrivée</label>
                    <input type="time" name="heure_arrivee" 
                           :readonly="hasArrivee"
                           :class="hasArrivee ? 'bg-gray-100 text-gray-400' : 'bg-white'"
                           class="w-full border-gray-200 rounded-xl text-sm font-bold">
                </div>
                <div>
                    <label class="block text-[9px] font-black text-gray-400 uppercase mb-1">Heure Départ</label>
                    <input type="time" name="heure_depart" 
                           :readonly="hasDepart || (!hasArrivee && selectedAgentId !== '')"
                           :disabled="!hasArrivee && !document.getElementsByName('heure_arrivee')[0]?.value"
                           :class="hasDepart ? 'bg-gray-100 text-gray-400' : 'bg-white'"
                           class="w-full border-gray-200 rounded-xl text-sm font-bold">
                </div>
            </div>

            <button type="submit" :disabled="!selectedAgentId"
                    class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black uppercase text-[10px] shadow-lg hover:bg-indigo-700 disabled:opacity-50 transition">
                Valider le pointage
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100">
            <button class="w-full bg-red-50 text-red-600 py-3 rounded-xl font-black uppercase text-[9px] hover:bg-red-600 hover:text-white transition">
                Clôturer la journée
            </button>
        </div>
    </div>
</div>
