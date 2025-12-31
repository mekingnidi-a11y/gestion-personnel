<x-app-layout>
    <x-slot name="header_title">Détails de la Direction</x-slot>

    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('directions.index') }}" class="p-2 bg-white rounded-xl shadow-sm hover:bg-gray-50 transition">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">{{ $direction->nom }}</h1>
        </div>
        @can('update', $direction)
        <a href="{{ route('directions.edit', $direction->code) }}" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg">
            Modifier
        </a>
        @endcan
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Infos Principales -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                <h3 class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-6">Missions & Attributions</h3>
                <div class="text-slate-600 leading-relaxed text-sm">
                    {!! nl2br(e($direction->missions ?? 'Aucune mission renseignée.')) !!}
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                <h3 class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-6">Sous-Directions ({{ $direction->enfants->count() }})</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($direction->enfants as $enfant)
                        <a href="{{ route('directions.show', $enfant->code) }}" class="p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-indigo-300 transition group">
                            <p class="font-bold text-slate-800 text-xs uppercase group-hover:text-indigo-600">{{ $enfant->nom }}</p>
                            <p class="text-[9px] text-gray-400 font-mono mt-1">{{ $enfant->code }}</p>
                        </a>
                    @empty
                        <p class="text-xs text-gray-400 italic">Aucune structure rattachée.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Infos -->
        <div class="space-y-6">
            <div class="bg-indigo-900 text-white p-8 rounded-[2.5rem] shadow-xl">
                <p class="text-[10px] font-black text-indigo-300 uppercase tracking-widest mb-4">Code Hiérarchique</p>
                <p class="text-2xl font-mono font-black mb-6">{{ $direction->code }}</p>
                
                <div class="space-y-4 border-t border-indigo-800 pt-6">
                    <div>
                        <p class="text-[9px] text-indigo-400 font-black uppercase">Type</p>
                        <p class="text-xs font-bold">{{ str_replace('_', ' ', $direction->type) }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] text-indigo-400 font-black uppercase">Parent</p>
                        <p class="text-xs font-bold">{{ $direction->parent->nom ?? 'Néant' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Contact</h3>
                <div class="space-y-3">
                    <p class="text-xs font-bold text-slate-700">{{ $direction->contact_email ?? 'Pas d\'email' }}</p>
                    <p class="text-xs font-bold text-slate-700">{{ $direction->contact_telephone ?? 'Pas de tel' }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
