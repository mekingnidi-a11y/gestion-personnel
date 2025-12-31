<x-app-layout>
    <x-slot name="header_title">Services Administratifs</x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="font-black text-xl text-slate-800 uppercase tracking-tight">
            {{ __('Répertoire des Services') }}
        </h2>
        
        {{-- Bouton ajouter visible pour les Admins (RH, DG, Direction) --}}
        @if(in_array(Auth::user()->role, ['admin_rh', 'admin_direction_generale', 'admin_direction']))
            <a href="{{ route('services.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-indigo-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-blue-200 transition-all active:scale-95">
                + Nouveau Service
            </a>
        @endif
    </div>

    <div class="bg-white overflow-hidden shadow-2xl rounded-[2.5rem] border border-gray-100">
        <div class="p-8 border-b border-gray-50 bg-white">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-gray-50">
                        <th class="px-6 py-5">Code Métier</th>
                        <th class="px-6 py-5">Désignation du Service</th>
                        <th class="px-6 py-5">Direction de Rattachement</th>
                        <th class="px-6 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($services as $service)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-5">
                                <span class="font-mono text-[11px] bg-blue-50 px-3 py-1 rounded-lg text-blue-700 font-bold">
                                    {{ $service->code }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="font-bold text-slate-800 text-sm uppercase">{{ $service->nom }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5 italic">
                                    {{ Str::limit($service->missions, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-[9px] font-black uppercase">
                                    {{ $service->direction->nom ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    {{-- Logique d'édition : RH ou Admin de la direction concernée --}}
                                    @php
                                        $user = Auth::user();
                                        $canEdit = ($user->role === 'admin_rh' || $service->direction_id === $user->direction_id);
                                    @endphp

                                    @if($canEdit)
                                        <a href="{{ route('services.edit', $service->id) }}" title="Modifier" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-xl transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                    @endif

                                    {{-- Bouton de suppression : SEUL ADMIN RH --}}
                                    @if($user->role === 'admin_rh')
                                        <form action="{{ route('services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce service ?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-400 hover:bg-red-50 rounded-xl transition">
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
                            <td colspan="4" class="px-6 py-10 text-center text-slate-500 italic">
                                Aucun service trouvé pour votre périmètre.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
