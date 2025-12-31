<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-indigo-900 uppercase">Affectations en attente de finalisation</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-amber-100">
            <div class="bg-amber-50 p-4 border-b border-amber-100">
                <p class="text-amber-700 text-xs font-bold uppercase">
                    Liste des agents orientés par la DGARH vers votre direction. Veuillez procéder à leur installation locale.
                </p>
            </div>
            
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-gray-500 uppercase">Agent</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-gray-500 uppercase">Date Recrutement</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-gray-500 uppercase">Direction Cible</th>
                        <th class="px-6 py-3 text-right text-[10px] font-black text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($agents as $agent)
                    <tr class="hover:bg-indigo-50/30 transition">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $agent->nom_complet }}</div>
                            <div class="text-xs text-gray-500 italic">{{ $agent->sexe == 'M' ? 'Né le' : 'Née le' }} {{ \Carbon\Carbon::parse($agent->date_naissance)->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-gray-600">
                            {{ \Carbon\Carbon::parse($agent->date_recrutement)->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-[10px] font-bold rounded uppercase">
                                {{ $agent->affectationActuelle->direction->nom ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('agents.prise-service.create', $agent->id) }}" 
                               class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase hover:bg-indigo-700 shadow-md transition">
                                Installer l'agent
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">Aucune affectation en attente pour le moment.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
