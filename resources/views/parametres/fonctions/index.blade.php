<x-app-layout>
    <div class="max-w-6xl mx-auto py-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-indigo-900 uppercase tracking-tight">Référentiel des Fonctions</h2>
            <a href="{{ route('fonctions.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold text-xs uppercase shadow-lg hover:bg-indigo-700 transition">
                + Ajouter une fonction
            </a>
        </div>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black text-indigo-900 uppercase">Direction</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-indigo-900 uppercase">Intitulé du Poste</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-indigo-900 uppercase">Type</th>
                        <th class="px-6 py-4 text-right text-xs font-black text-indigo-900 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($fonctions as $fonction)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-bold text-gray-700 uppercase">{{ $fonction->direction->nom ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $fonction->intitule }}</td>
                        <td class="px-6 py-4">
                            @if($fonction->est_responsabilite)
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 text-[10px] font-black rounded-full uppercase">Responsabilité</span>
                            @else
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-black rounded-full uppercase">Exécution</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-indigo-600 hover:text-indigo-900 text-xs font-bold uppercase">Modifier</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
