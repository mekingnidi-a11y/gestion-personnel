<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 uppercase tracking-tight">Fiche Agent : {{ $agent->nom_complet }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('agents.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-bold text-xs">Retour</a>
            @can('update', $agent)
                <a href="{{ route('agents.edit', $agent->id) }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg font-bold text-xs">Modifier la fiche</a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Informations Personnelles -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-indigo-600 font-black text-xs uppercase mb-4 border-b pb-2">Identité</h3>
            <div class="space-y-4">
                <div><p class="text-[10px] text-gray-400 uppercase font-bold">Matricule</p><p class="font-mono font-bold">{{ $agent->matricule ?? 'NON ASSIGNÉ' }}</p></div>
                <div><p class="text-[10px] text-gray-400 uppercase font-bold">Sexe</p><p class="font-bold">{{ $agent->sexe == 'M' ? 'Masculin' : 'Féminin' }}</p></div>
                <div><p class="text-[10px] text-gray-400 uppercase font-bold">Naissance</p><p class="font-bold">{{ $agent->date_naissance ? \Carbon\Carbon::parse($agent->date_naissance)->format('d/m/Y') : 'N/A' }}</p></div>
                <div><p class="text-[10px] text-gray-400 uppercase font-bold">Statut Actuel</p><span class="inline-block bg-green-100 text-green-700 px-2 py-1 rounded text-[10px] font-black uppercase">{{ $agent->statut }}</span></div>
            </div>
        </div>

        <!-- Affectation Actuelle -->
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-500">
            <h3 class="text-indigo-600 font-black text-xs uppercase mb-4 border-b pb-2">Affectation</h3>
            @if($agent->affectationActuelle)
                <div class="space-y-4">
                    <div><p class="text-[10px] text-gray-400 uppercase font-bold">Direction</p><p class="font-bold text-sm uppercase">{{ $agent->affectationActuelle->direction->nom ?? 'N/A' }}</p></div>
                    <div><p class="text-[10px] text-gray-400 uppercase font-bold">Fonction</p><p class="font-bold text-sm">{{ $agent->affectationActuelle->fonction ?? 'Agent' }}</p></div>
                    <div><p class="text-[10px] text-gray-400 uppercase font-bold">Prise de service</p><p class="font-bold text-sm">{{ \Carbon\Carbon::parse($agent->affectationActuelle->date_debut)->format('d/m/Y') }}</p></div>
                </div>
            @else
                <p class="text-red-500 text-xs italic">Aucune affectation active.</p>
            @endif
        </div>

        <!-- Recrutement -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-indigo-600 font-black text-xs uppercase mb-4 border-b pb-2">Recrutement</h3>
            <div class="space-y-4">
                <div><p class="text-[10px] text-gray-400 uppercase font-bold">N° Acte</p><p class="font-bold text-sm">{{ $agent->num_recrutement }}</p></div>
                <div><p class="text-[10px] text-gray-400 uppercase font-bold">Grade Initial</p><p class="font-bold text-sm">{{ $agent->grade_recrutement }}</p></div>
                <div><p class="text-[10px] text-gray-400 uppercase font-bold">Date Recrutement</p><p class="font-bold text-sm">{{ \Carbon\Carbon::parse($agent->date_recrutement)->format('d/m/Y') }}</p></div>
            </div>
        </div>
    </div>

    <!-- Historique -->
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50"><h3 class="text-gray-700 font-black text-xs uppercase">Historique des Affectations</h3></div>
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b">
                <tr class="text-[10px] font-bold text-gray-400 uppercase">
                    <th class="px-6 py-3">Période</th>
                    <th class="px-6 py-3">Direction</th>
                    <th class="px-6 py-3">Acte</th>
                    <th class="px-6 py-3 text-center">État</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($agent->affectations->sortByDesc('date_debut') as $aff)
                <tr class="text-xs">
                    <td class="px-6 py-4">Du {{ \Carbon\Carbon::parse($aff->date_debut)->format('d/m/Y') }} {{ $aff->date_fin ? 'au '.\Carbon\Carbon::parse($aff->date_fin)->format('d/m/Y') : '' }}</td>
                    <td class="px-6 py-4 font-bold uppercase">{{ $aff->direction->nom ?? 'N/A' }}</td>
                    <td class="px-6 py-4 font-mono">{{ $aff->ref_acte }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($aff->est_actuelle)
                            <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-black text-[9px]">ACTUEL</span>
                        @else
                            <span class="text-gray-400 font-bold text-[9px]">ARCHIVÉ</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
