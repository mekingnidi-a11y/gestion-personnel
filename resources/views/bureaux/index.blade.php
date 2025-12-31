<x-app-layout>
    @section('header-title', 'Répertoire des Bureaux')

    <div class="flex justify-between items-center mb-6 transition-colors duration-300">
        <h2 class="font-black text-xl text-slate-800 dark:text-white uppercase tracking-tight">Gestion des Bureaux</h2>
        @if(in_array(Auth::user()->role, ['admin_rh', 'admin_direction_generale', 'admin_direction']))
            <a href="{{ route('bureaux.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-blue-200 dark:shadow-none transition-all active:scale-95">
                + Nouveau Bureau
            </a>
        @endif
    </div>

    <!-- TABLEAU DES BUREAUX ADAPTATIF -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl rounded-[2.5rem] border border-gray-100 dark:border-gray-700 transition-colors duration-300">
        <div class="p-8 border-b border-gray-50 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-[0.2em] border-b border-gray-50 dark:border-gray-700">
                            <th class="px-6 py-5">Code Bureau</th>
                            <th class="px-6 py-5">Désignation & Localisation</th>
                            <th class="px-6 py-5">Service Rattaché</th>
                            <th class="px-6 py-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @forelse($bureaux as $bureau)
                            <tr class="hover:bg-blue-50/30 dark:hover:bg-indigo-900/10 transition-colors group">
                                <td class="px-6 py-5">
                                    <span class="font-mono text-[11px] bg-indigo-50 dark:bg-indigo-900/40 px-3 py-1 rounded-lg text-indigo-700 dark:text-indigo-300 font-bold">
                                        {{ $bureau->code }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="font-bold text-slate-800 dark:text-white text-sm uppercase leading-tight">{{ $bureau->nom }}</div>
                                    <div class="text-[10px] text-slate-400 dark:text-gray-500 mt-0.5 italic uppercase font-medium">
                                        Localisation: {{ $bureau->localisation ?? 'Non spécifiée' }}
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-300 text-[9px] font-black uppercase tracking-tighter">
                                        {{ $bureau->service->nom ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex justify-end gap-2">
                                        {{-- LIEN VOIR --}}
                                        <a href="{{ route('bureaux.show', $bureau->id) }}" class="p-2 text-slate-400 dark:text-gray-500 hover:bg-slate-100 dark:hover:bg-gray-700 rounded-xl transition" title="Détails">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>

                                        @php
                                            $user = Auth::user();
                                            $canEdit = ($user->role === 'admin_rh' || ($bureau->service && $bureau->service->direction_id === $user->direction_id));
                                        @endphp

                                        @if($canEdit)
                                            <a href="{{ route('bureaux.edit', $bureau->id) }}" class="p-2 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-xl transition" title="Modifier">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                        @endif

                                        @if($user->role === 'admin_rh')
                                            <form action="{{ route('bureaux.destroy', $bureau->id) }}" method="POST" onsubmit="return confirm('Supprimer ce bureau ?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2 text-red-400 dark:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-xl transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <p class="text-slate-500 dark:text-gray-600 italic font-bold uppercase text-[10px] tracking-widest">Aucun bureau enregistré dans le répertoire.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
