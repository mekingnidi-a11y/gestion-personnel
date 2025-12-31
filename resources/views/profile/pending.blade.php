<x-app-layout>
    <x-slot name="header-title">Gestion des comptes et sécurité</x-slot>

    <div class="py-12 px-4 lg:px-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-[2rem] overflow-hidden border border-gray-100">
            <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-xl font-black text-slate-800 uppercase italic">Administration des accès</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Valider, bloquer ou supprimer des utilisateurs</p>
                </div>
                <span class="bg-indigo-100 text-indigo-600 px-4 py-1 rounded-full text-[10px] font-black tracking-widest uppercase">
                    {{ $users->count() }} Compte(s) listés
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[10px] font-black uppercase text-slate-400 border-b border-gray-100">
                        <tr>
                            <th class="p-6">Utilisateur / Direction</th>
                            <th class="p-6">Statut & Alerte</th>
                            <th class="p-6 text-right">Actions de gestion</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="p-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-500 uppercase">
                                        {{ substr($user->username, 0, 2) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-800 block text-sm">{{ $user->username }}</span>
                                        <span class="text-[9px] text-indigo-500 font-black uppercase">{{ $user->direction->sigle ?? 'Non assignée' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                @if(!$user->est_valide)
                                    <div class="flex items-center text-blue-600">
                                        <span class="w-2 h-2 bg-blue-600 rounded-full mr-2 animate-ping"></span>
                                        <span class="text-[10px] font-black uppercase">En attente d'activation</span>
                                    </div>
                                @elseif($user->a_demande_reset)
                                    <div class="flex items-center text-amber-600">
                                        <span class="w-2 h-2 bg-amber-600 rounded-full mr-2 animate-ping"></span>
                                        <span class="text-[10px] font-black uppercase tracking-tighter">Réinitialisation requise</span>
                                    </div>
                                @else
                                    <div class="flex items-center text-green-600">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        <span class="text-[10px] font-black uppercase">Compte Actif</span>
                                    </div>
                                @endif
                            </td>
                            <td class="p-6">
                                <div class="flex justify-end items-center space-x-2">
                                    <!-- TRAITER / MODIFIER -->
                                    <a href="{{ route('users.edit-validation', $user->id) }}" 
                                       class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition shadow-sm" title="Modifier / Valider">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </a>

                                    <!-- BLOQUER / ACTIVER -->
                                    <form method="POST" action="{{ route('users.toggle', $user->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="p-2 rounded-lg transition shadow-sm {{ $user->est_valide ? 'bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white' : 'bg-green-50 text-green-600 hover:bg-green-600 hover:text-white' }}"
                                                title="{{ $user->est_valide ? 'Bloquer l\'accès' : 'Activer l\'accès' }}">
                                            @if($user->est_valide)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            @endif
                                        </button>
                                    </form>

                                    <!-- SUPPRIMER -->
                                    <form method="POST" action="{{ route('users.delete', $user->id) }}" onsubmit="return confirm('Supprimer définitivement ce compte ? Cette action est irréversible.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition shadow-sm" title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($users->isEmpty())
                <div class="p-20 text-center">
                    <p class="text-xs font-black text-slate-300 uppercase tracking-[0.2em]">Aucune demande ou alerte en cours</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
