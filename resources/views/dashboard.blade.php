<x-app-layout>
    @section('header-title', 'Tableau de Bord')

    <div class="py-6 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- HEADER DYNAMIQUE -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-800 dark:text-white uppercase tracking-tight">
                        @if(Auth::user()->role === 'admin_rh')
                            Administration Centrale (RH)
                        @elseif(Auth::user()->role === 'admin_direction_generale')
                            Direction Générale
                        @else
                            Direction : {{ Auth::user()->code_direction }}
                        @endif
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">
                        Bienvenue, <span class="text-indigo-600 dark:text-indigo-400 font-bold">{{ Auth::user()->username }}</span>. 
                        Voici l'état actuel de votre périmètre.
                    </p>
                </div>
                
                {{-- Badge de rôle --}}
                <div class="px-4 py-2 bg-indigo-100 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-800 rounded-full w-fit">
                    <span class="text-[10px] font-black text-indigo-700 dark:text-indigo-300 uppercase tracking-widest">
                        Session : {{ str_replace('_', ' ', Auth::user()->role) }}
                    </span>
                </div>
            </div>

            <!-- GRILLE DES STATISTIQUES PRINCIPALES -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                
                <!-- Carte Agents -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute right-0 top-0 h-full w-1.5 bg-indigo-600"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Total Agents</p>
                            <h3 class="text-4xl font-black text-gray-900 dark:text-white">{{ $stats['total_agents'] ?? 0 }}</h3>
                        </div>
                        <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl text-indigo-600 dark:text-indigo-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Carte En Attente -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute right-0 top-0 h-full w-1.5 bg-orange-500"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">En attente installation</p>
                            <h3 class="text-4xl font-black text-gray-900 dark:text-white">{{ $stats['en_attente'] ?? 0 }}</h3>
                        </div>
                        <div class="p-3 bg-orange-50 dark:bg-orange-900/20 rounded-xl text-orange-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Carte Sans Matricule -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute right-0 top-0 h-full w-1.5 bg-red-600"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Sans Matricule</p>
                            <h3 class="text-4xl font-black text-gray-900 dark:text-white">{{ $stats['sans_matricule'] ?? 0 }}</h3>
                        </div>
                        <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-xl text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0h4m-6 7a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTIONS SPÉCIFIQUES PAR RÔLE -->
            @if(Auth::user()->role === 'admin_rh')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Alertes Administration -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                        <h4 class="text-sm font-black text-gray-800 dark:text-white uppercase mb-4 flex items-center">
                            <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                            Alertes Système
                        </h4>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <span class="text-xs font-bold dark:text-gray-300">Comptes & Resets en attente</span>
                                <span class="px-2 py-1 bg-red-600 text-white text-[10px] font-black rounded">{{ $stats['users_en_attente'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <span class="text-xs font-bold dark:text-gray-300">Total Directions</span>
                                <span class="px-2 py-1 bg-indigo-600 text-white text-[10px] font-black rounded">{{ $stats['total_directions'] }}</span>
                            </div>
                        </div>
                        <a href="{{ route('users.pending') }}" class="mt-6 block text-center p-3 text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition shadow-lg shadow-indigo-200 dark:shadow-none">
                            Gérer les validations
                        </a>
                    </div>

                    <!-- Actions Rapides Admin RH -->
                    <div class="bg-indigo-900 rounded-2xl p-6 text-white relative overflow-hidden">
                        <svg class="absolute -right-10 -bottom-10 w-40 h-40 text-white/10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path></svg>
                        <h4 class="text-sm font-black uppercase mb-4">Outils Centralisés</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('agents.index') }}" class="p-3 bg-white/10 hover:bg-white/20 rounded-xl text-[10px] font-black uppercase text-center transition border border-white/10">Extraction Globale</a>
                            <a href="{{ route('pointages.rapports') }}" class="p-3 bg-white/10 hover:bg-white/20 rounded-xl text-[10px] font-black uppercase text-center transition border border-white/10">Rapports Nationaux</a>
                        </div>
                    </div>
                </div>
            @endif

            @if(in_array(Auth::user()->role, ['admin_direction', 'admin_direction_generale']))
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-sm font-black text-gray-800 dark:text-white uppercase">Services sous votre direction</h4>
                        <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-xs font-black rounded-lg">
                            {{ $stats['total_services'] }} Services
                        </span>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('pointages.index') }}" class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl hover:border-indigo-500 border border-transparent transition group">
                            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase mb-2">Suivi</p>
                            <p class="text-xs font-bold dark:text-white group-hover:text-indigo-600 transition">Pointages du jour</p>
                        </a>
                        <a href="{{ route('prises-service.index') }}" class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl hover:border-orange-500 border border-transparent transition group">
                            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase mb-2">Installation</p>
                            <p class="text-xs font-bold dark:text-white group-hover:text-orange-500 transition">Nouvelles arrivées</p>
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
