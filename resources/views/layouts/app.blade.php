<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Gestion RH') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .sidebar-link-active { background: rgba(255,255,255,0.15); border-left: 4px solid #fff; color: #fff !important; }
            .custom-scrollbar::-webkit-scrollbar { width: 4px; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-100 text-gray-900 overflow-hidden">
        <div class="flex h-screen overflow-hidden">
            
            <!-- SIDEBAR -->
            <aside class="w-64 bg-indigo-900 text-white flex-shrink-0 flex flex-col shadow-2xl">
                <div class="p-6 flex flex-col items-center border-b border-indigo-800">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center overflow-hidden shadow-lg border-2 border-indigo-400">
                        <img src="{{ asset('images/logo_ministere.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="mt-3 text-[9px] font-black text-center uppercase tracking-widest text-indigo-200">
                        Ministère Enseignement <br> Technique & Pro
                    </span>
                </div>

                <nav class="flex-1 mt-5 px-3 space-y-1 overflow-y-auto custom-scrollbar">
                    <x-nav-link-custom href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3">
                        Dashboard
                    </x-nav-link-custom>

                    @php $user = Auth::user(); @endphp

                    {{-- BOUTON VALIDATION & RÉINITIALISATION POUR ADMINS --}}
                    @if(in_array($user->role, ['admin_rh', 'admin_direction_generale', 'admin_direction']))
                        <x-nav-link-custom href="{{ route('users.pending') }}" :active="request()->routeIs('users.pending')" icon="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            Validation Comptes
                            @php 
                                $alertCount = \App\Models\User::where('est_valide', false)
                                            ->orWhere('a_demande_reset', true)->count();
                            @endphp
                            @if($alertCount > 0)
                                <span class="ml-2 bg-red-600 text-white text-[10px] px-2 py-0.5 rounded-full font-black animate-pulse shadow-lg border border-red-400">
                                    {{ $alertCount }}
                                </span>
                            @endif
                        </x-nav-link-custom>
                    @endif

                    <div class="pt-4 pb-2 px-3 text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Organigramme</div>
                    
                    @if(in_array($user->role, ['admin_rh', 'admin_direction_generale', 'admin_direction', 'chef_service']))
                        <x-nav-link-custom href="{{ route('directions.index') }}" :active="request()->routeIs('directions.*')" icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16">
                            Directions
                        </x-nav-link-custom>
                    @endif

                    <x-nav-link-custom href="{{ route('services.index') }}" :active="request()->routeIs('services.*')" icon="M4 6h16">
                        Services
                    </x-nav-link-custom>

                    <x-nav-link-custom href="{{ route('bureaux.index') }}" :active="request()->routeIs('bureaux.*')" icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16">
                        Bureaux
                    </x-nav-link-custom>

                    <div class="pt-6 pb-2 px-3 text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Temps & Présences</div>

                    <x-nav-link-custom href="{{ route('pointages.index') }}" :active="request()->routeIs('pointages.*')" icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                        Gestion Pointages
                    </x-nav-link-custom>

                    <x-nav-link-custom href="{{ route('pointages.rapports') }}" :active="request()->routeIs('pointages.rapports')" icon="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        Rapports & Stats
                    </x-nav-link-custom>

                    <div class="pt-6 pb-2 px-3 text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Ressources Humaines</div>

                    <x-nav-link-custom href="{{ route('agents.index') }}" :active="request()->routeIs('agents.*') && !request()->routeIs('agents.matricule.*') && !request()->routeIs('agents.mutation.*')" icon="M12 4.354a4 4 0 110 5.292">
                        Liste Agents
                    </x-nav-link-custom>

                    @if($user->role !== 'agent')
                        <x-nav-link-custom href="{{ route('prises-service.index') }}" :active="request()->routeIs('prises-service.index', 'agents.prise-service.*')" icon="M19 11H5">
                            Arrivées
                            @php 
                                $countArr = \App\Models\Agent::where('est_synchronise', 0)
                                            ->whereHas('affectationActuelle', function($q) use ($user) {
                                                if($user->role !== 'admin_rh') { $q->where('code_direction', $user->direction_id); }
                                            })->count();
                            @endphp
                            @if($countArr > 0)
                                <span class="ml-2 bg-red-500 text-white text-[9px] px-1.5 py-0.5 rounded-full font-black animate-pulse">{{ $countArr }}</span>
                            @endif
                        </x-nav-link-custom>

                        <x-nav-link-custom href="{{ route('agents.mutation.index') }}" :active="request()->routeIs('agents.mutation.*')" icon="M8 7h12m0 0l-4-4m4 4l-4 4">
                            Mutations
                        </x-nav-link-custom>

                        <x-nav-link-custom href="{{ route('agents.matricule.index') }}" :active="request()->routeIs('agents.matricule.index')" icon="M9 12h6m-6 4h6">
                            Matricules
                            @php 
                                $countMat = \App\Models\Agent::whereNull('matricule')
                                            ->whereHas('affectationActuelle', function($q) use ($user) {
                                                if($user->role !== 'admin_rh') { $q->where('code_direction', $user->direction_id); }
                                            })->count();
                            @endphp
                            @if($countMat > 0)
                                <span class="ml-2 bg-yellow-500 text-black text-[9px] px-1.5 py-0.5 rounded-full font-black">{{ $countMat }}</span>
                            @endif
                        </x-nav-link-custom>
                    @endif
                </nav>

                <!-- USER INFO & LOGOUT -->
                <div class="p-4 border-t border-indigo-800">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 bg-indigo-500 rounded flex items-center justify-center text-xs font-bold uppercase text-white">
                            {{ strtoupper(substr($user->username ?? $user->name, 0, 2)) }}
                        </div>

                        @php
                            $unreadCount = \App\Models\Message::where(function($q) {
                                    $q->where('receiver_id', auth()->id())->whereNull('read_at');
                                })
                                ->orWhere(function($q) {
                                    $q->where('est_diffusion', true)
                                      ->whereDoesntHave('readers', fn($sq) => $sq->where('user_id', auth()->id()));
                                })->count();
                        @endphp

                        <a href="{{ route('messages.index') }}" class="relative flex items-center p-2 text-indigo-200 hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-[8px] font-black px-1.5 py-0.5 rounded-full animate-pulse text-white">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>

                        <div class="overflow-hidden">
                            <p class="text-xs font-bold truncate text-white">{{ $user->username ?? $user->name }}</p>
                            <p class="text-[9px] text-indigo-400 uppercase font-black">{{ str_replace('_', ' ', $user->role) }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-2 text-xs font-bold text-indigo-300 hover:text-white rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </aside>

            <!-- CONTENU PRINCIPAL -->
            <main class="flex-1 flex flex-col overflow-hidden">
                <header class="bg-white h-16 border-b flex items-center px-8 justify-between shadow-sm">
                    <h2 class="font-bold text-gray-800 uppercase tracking-wider text-xs">@yield('header-title', 'Système de Gestion RH')</h2>
                    <div class="text-[10px] font-bold text-gray-400 uppercase">
                        {{ now(new \DateTimeZone('Africa/Brazzaville'))->translatedFormat('d F Y') }} | <span class="text-indigo-600 font-black uppercase">{{ $user->code_direction ?? 'ADMINISTRATION CENTRALE' }}</span>
                    </div>
                </header>

                <div class="flex-1 overflow-y-auto p-8 custom-scrollbar bg-gray-50/50">
                    @if (session('success'))
                        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 text-green-800 text-xs font-bold shadow-sm rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 text-blue-800 text-xs font-bold shadow-sm rounded-lg">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 text-red-800 text-xs font-bold shadow-sm rounded-lg">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
