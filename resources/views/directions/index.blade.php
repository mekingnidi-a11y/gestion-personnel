<x-app-layout>
    <x-slot name="header_title">Répertoire des Directions</x-slot>

    <div class="flex justify-between items-center mb-6 transition-colors duration-300">
        <h2 class="font-black text-xl text-slate-800 dark:text-white uppercase tracking-tight">
            {{ __('Répertoire des Directions') }}
        </h2>
        
        {{-- SEUL ADMIN RH PEUT AJOUTER --}}
        @if(Auth::user()->role === 'admin_rh')
            <a href="{{ route('directions.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-indigo-900 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-blue-200 dark:shadow-none transition-all active:scale-95">
                + Nouvelle Direction
            </a>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl rounded-[2.5rem] border border-gray-100 dark:border-gray-700 transition-colors duration-300">
        <div class="p-8 border-b border-gray-50 dark:border-gray-700 bg-white dark:bg-gray-800 transition-colors duration-300">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-[0.2em] border-b border-gray-50 dark:border-gray-700">
                        <th class="px-6 py-5">Identifiant (Code)</th>
                        <th class="px-6 py-5">Nom & Parent</th>
                        <th class="px-6 py-5 text-center">Type</th>
                        <th class="px-6 py-5 text-center">Synchronisation</th>
                        <th class="px-6 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($directions as $direction)
                        <tr class="hover:bg-blue-50/30 dark:hover:bg-gray-700/50 transition-colors group">
                            <td class="px-6 py-5">
                                <span class="font-mono text-[11px] bg-slate-100 dark:bg-gray-900/50 px-3 py-1 rounded-lg text-slate-600 dark:text-gray-400 font-bold">
                                    {{ $direction->code }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="font-bold text-slate-800 dark:text-gray-200 text-sm">{{ $direction->nom }}</div>
                                <div class="text-[10px] text-slate-400 dark:text-gray-500 uppercase mt-0.5">
                                    Parent: {{ $direction->parent->nom ?? 'Racine' }}
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-400 text-[9px] font-black uppercase">
                                    {{ str_replace('_', ' ', $direction->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="h-2.5 w-2.5 rounded-full inline-block {{ $direction->est_synchronise ? 'bg-green-500' : 'bg-orange-400' }}"></span>
                                <span class="text-[9px] ml-1 text-slate-400 dark:text-gray-500">{{ $direction->est_synchronise ? 'OK' : 'En attente' }}</span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    
                                    {{-- LOGIQUE DE MODIFICATION --}}
                                    @php
                                        $user = Auth::user();
                                        $canEdit = false;

                                        if($user->role === 'admin_rh') {
                                            $canEdit = true;
                                        } 
                                        elseif (in_array($user->role, ['admin_direction_generale', 'admin_direction'])) {
                                            $canEdit = ($user->direction_id === $direction->id);
                                        }
                                    @endphp

                                    @if($canEdit)
                                        <a href="{{ route('directions.edit', $direction->id) }}" title="Modifier" class="p-2 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-xl transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                    @endif
                                    
                                    {{-- LIEN VOIR --}}
                                    <a href="{{ route('directions.show', $direction->id) }}" title="Détails" class="p-2 text-slate-400 dark:text-gray-500 hover:bg-slate-100 dark:hover:bg-gray-700 rounded-xl transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>

                                    {{-- BOUTON SUPPRIMER (Admin RH uniquement) --}}
                                    @if($user->role === 'admin_rh')
                                        <form action="{{ route('directions.destroy', $direction->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette direction ?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-400 dark:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-xl transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500 dark:text-gray-400 italic">
                                Aucune direction enregistrée dans le répertoire.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
