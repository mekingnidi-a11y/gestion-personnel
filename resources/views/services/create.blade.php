<x-app-layout>
    <x-slot name="header_title">Nouveau Service</x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('services.index') }}" class="p-2 bg-white rounded-xl shadow-sm hover:bg-gray-50 transition">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-xl font-black text-slate-800 uppercase tracking-tight">Création d'un service</h1>
        </div>

        <form action="{{ route('services.store') }}" method="POST" class="bg-white p-10 rounded-[2.5rem] shadow-2xl border border-gray-100">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Désignation -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Désignation du Service</label>
                    <input type="text" name="nom" placeholder="Ex: Maintenance Informatique" required 
                        class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold text-sm focus:ring-blue-500 focus:border-blue-500 py-3 shadow-sm">
                    @error('nom') <span class="text-red-500 text-[9px] font-bold uppercase">{{ $message }}</span> @enderror
                </div>

                <!-- Direction (Verrouillage automatique selon rôle) -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Direction de rattachement</label>
                    
                    @if(Auth::user()->role === 'admin_rh')
                        {{-- L'admin RH choisit la direction --}}
                        <select name="direction_id" required class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold text-sm py-3">
                            <option value="">-- Sélectionner la Direction --</option>
                            @foreach($directions as $direction)
                                <option value="{{ $direction->id }}">{{ $direction->nom }}</option>
                            @endforeach
                        </select>
                    @else
                        {{-- Les autres admins sont verrouillés sur leur propre direction --}}
                        <div class="w-full rounded-2xl border-gray-100 bg-gray-200 font-bold text-sm py-3 px-4 text-slate-500 cursor-not-allowed">
                            {{ Auth::user()->direction->nom ?? 'Ma Direction' }}
                        </div>
                        <input type="hidden" name="direction_id" value="{{ Auth::user()->direction_id }}">
                    @endif
                </div>
            </div>

            <!-- Missions -->
            <div class="mb-10 space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Missions & Attributions</label>
                <textarea name="missions" rows="5" placeholder="Décrivez brièvement les missions de ce service..."
                    class="w-full rounded-2xl border-gray-100 bg-gray-50 font-medium text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm p-4"></textarea>
            </div>

            <div class="flex items-center justify-end gap-6 pt-6 border-t border-gray-50">
                <a href="{{ route('services.index') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition">
                    Annuler
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-indigo-900 text-white px-10 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-blue-100 transition-all active:scale-95">
                    Confirmer la création
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
