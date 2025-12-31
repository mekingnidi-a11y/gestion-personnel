<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Gestion RH') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="fonts.bunny.net" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .sidebar-link-active { background: rgba(255,255,255,0.15); border-left: 4px solid #fff; color: #fff !important; }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="flex h-screen overflow-hidden">
            
            <!-- SIDEBAR (Menu Vertical Gauche) -->
            <aside class="w-64 bg-indigo-900 text-white flex-shrink-0 flex flex-col transition-all duration-300">
                <!-- Zone Logo / Image -->
                <div class="p-6 flex flex-col items-center border-b border-indigo-800">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center overflow-hidden shadow-lg border-2 border-indigo-400">
                        <img src="{{ asset('images/logo_ministere.png') }}" alt="Logo" class="w-full h-full object-cover" onerror="this.src='ui-avatars.com'">
                    </div>
                    <span class="mt-3 text-[10px] font-black text-center uppercase tracking-widest text-indigo-200 leading-tight">
                        Ministère Enseignement <br> Technique & Pro
                    </span>
                </div>

                <!-- Liens de navigation -->
                <nav class="flex-1 mt-5 px-3 space-y-1 overflow-y-auto">
                    
                    <!-- DASHBOARD -->
                    <x-nav-link-custom href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        Dashboard
                    </x-nav-link-custom>

                    <!-- SECTION : ADMINISTRATION DES STRUCTURES -->
                    <div class="pt-4 pb-2 px-3 text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Structures & Organigramme</div>
                    
                    <x-nav-link-custom href="{{ route('directions.index') }}" :active="request()->routeIs('directions.*')" icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        Directions
                    </x-nav-link-custom>

                    <x-nav-link-custom href="{{ route('services.index') }}" :active="request()->routeIs('services.*')" icon="M4 6h16M4 10h16M4 14h16M4 18h16">
                        Services
                    </x-nav-link-custom>

                    <x-nav-link-custom href="{{ route('bureaux.index') }}" :active="request()->routeIs('bureaux.*')" icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3">
                        Bureaux
                    </x-nav-link-custom>

                    <!-- SECTION : GESTION DU PERSONNEL -->
                    <div class="pt-6 pb-2 px-3 text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Ressources Humaines</div>

                    <x-nav-link-custom href="{{ route('agents.index') }}" :active="request()->routeIs('agents.index', 'agents.show', 'agents.edit')" icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        Liste des Agents
                    </x-nav-link-custom>

                    <!-- Nouveau lien : Prise de Service (Agents en attente d'affectation locale) -->
            <x-nav-link-custom href="{{ route('affectations.en-attente') }}" :active="request()->routeIs('affectations.en-attente')" icon="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
    Arrivées / Installations
    
    @php 
        // Compte uniquement les agents affectés à une direction mais sans prise de service
        $pendingCount = \App\Models\Agent::whereNull('date_premiere_prise_service')
            ->whereHas('affectationActuelle')
            ->count(); 
    @endphp

    @if($pendingCount > 0)
        <span class="ml-2 bg-red-500 text-white text-[9px] px-2 py-0.5 rounded-full animate-pulse font-black">
            {{ $pendingCount }}
        </span>
    @endif
</x-nav-link-custom>

<!-- Dans la section : GESTION DU PERSONNEL du sidebar -->
<x-nav-link-custom href="{{ route('agents.mutation.index') }}" :active="request()->routeIs('agents.mutation.*')" icon="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4">
    Mutations Internes
</x-nav-link-custom>

<x-nav-link-custom href="{{ route('agents.matricule.index') }}" :active="request()->routeIs('agents.matricule.*')" icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
    Attribution Matricule
    
    @php 
        // On compte les agents installés (date_premiere_prise_service non nulle) 
        // MAIS qui n'ont pas encore de matricule
        $noMatriculeCount = \App\Models\Agent::whereNotNull('date_premiere_prise_service')
            ->where(function($q) {
                $q->whereNull('matricule')->orWhere('matricule', '');
            })
            ->count(); 
    @endphp

    @if($noMatriculeCount > 0)
        <span class="ml-2 bg-amber-500 text-white text-[9px] px-2 py-0.5 rounded-full animate-bounce font-black">
            {{ $noMatriculeCount }}
        </span>
    @endif
</x-nav-link-custom>



                    <!-- SECTION : CONFIGURATION / RÉFÉRENTIELS -->
                    <div class="pt-6 pb-2 px-3 text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Configuration</div>

                    <x-nav-link-custom href="{{ route('fonctions.index') }}" :active="request()->routeIs('fonctions.*')" icon="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745V6c0-1.105.895-2 2-2h14a2 2 0 012 2v7.255zM12 11a1 1 0 100-2 1 1 0 000 2z">
                        Référentiel Fonctions
                    </x-nav-link-custom>

                </nav>

                <!-- Zone Utilisateur / Déconnexion -->
                <div class="p-4 border-t border-indigo-800 bg-indigo-950">
                    <div class="flex items-center mb-4 px-2">
                        <div class="h-8 w-8 rounded bg-indigo-500 flex items-center justify-center text-white font-bold text-xs uppercase">
                            {{ substr(Auth::user()->name ?? Auth::user()->username, 0, 1) }}
                        </div>
                        <div class="ml-3 overflow-hidden">
                            <p class="text-[11px] font-bold text-white truncate">{{ Auth::user()->name ?? Auth::user()->username }}</p>
                            <p class="text-[9px] text-indigo-400 uppercase font-black">Session 2025</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-2 text-[11px] font-black text-indigo-300 hover:text-white hover:bg-red-600 rounded-xl transition duration-200 uppercase tracking-tighter">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            Quitter le système
                        </button>
                    </form>
                </div>
            </aside>

            <!-- CONTENU PRINCIPAL -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Topbar -->
                <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 border-b border-gray-200">
                    <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest">@yield('header-title', 'Système RH Décentralisé')</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-[10px] font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full border border-green-200 uppercase tracking-tighter flex items-center">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                            Mode Local Actif (Synchronisable)
                        </span>
                    </div>
                </header>

                <!-- Zone de contenu -->
                <main class="flex-1 overflow-y-auto p-8 bg-gray-50/50">
                    @if(isset($slot))
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endif
                </main>
            </div>
        </div>
    </body>
</html>
