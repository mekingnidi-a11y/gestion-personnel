<x-app-layout>
    @section('header-title', 'Attribution des Matricules')

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Barre de recherche -->
        <div class="mb-6 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('agents.matricule.index') }}" method="GET" class="flex gap-4">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher par nom ou prénom..." 
                           class="w-full pl-10 border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium">
                </div>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2 rounded-xl font-black uppercase text-[10px] transition shadow-lg shadow-indigo-200">
                    Rechercher
                </button>
                @if(request('search'))
                    <a href="{{ route('agents.matricule.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-500 px-4 py-2 rounded-xl font-black uppercase text-[10px] flex items-center">
                        Effacer
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-900">
                    <tr>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-indigo-100 uppercase tracking-widest">Agent (Nom & Prénom)</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-indigo-100 uppercase tracking-widest">Direction / Service</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-indigo-100 uppercase tracking-widest">Saisir le Matricule</th>
                        <th class="px-6 py-5 text-right text-[10px] font-black text-indigo-100 uppercase tracking-widest">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($agents as $agent)
                    <tr class="hover:bg-indigo-50/30 transition group">
                        <td class="px-6 py-4">
                            <div class="text-sm font-black text-indigo-900 uppercase tracking-tighter">{{ $agent->nom_complet }}</div>
                            <div class="text-[9px] text-gray-400 font-bold uppercase italic mt-1">ID: {{ $agent->id }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[10px] font-black text-gray-700 uppercase">
                                {{ $agent->affectationActuelle->direction->nom ?? 'N/A' }}
                            </div>
                            <div class="text-[9px] text-indigo-500 font-bold uppercase mt-0.5">
                                {{ $agent->affectationActuelle->fonction ?? 'Sans fonction' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <form id="form-{{ $agent->id }}" action="{{ route('agents.matricule.update', $agent->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="matricule" required 
                                       placeholder="Ex: 123456X" 
                                       class="border-gray-300 rounded-xl text-xs font-black uppercase focus:ring-indigo-500 focus:border-indigo-500 w-full max-w-[180px] bg-gray-50 group-hover:bg-white transition">
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button type="submit" form="form-{{ $agent->id }}" 
                                    class="bg-white border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-600 hover:text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase transition-all shadow-sm active:scale-95">
                                Enregistrer
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Aucun agent en attente de matricule</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($agents, 'links'))
            <div class="mt-6">
                {{ $agents->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
