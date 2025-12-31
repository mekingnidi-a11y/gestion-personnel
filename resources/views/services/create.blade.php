<x-app-layout>
    <x-slot name="header_title">Nouveau Service</x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4 transition-colors duration-300">
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('services.index') }}" class="p-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 border border-transparent dark:border-gray-700 transition">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Création d'un service</h1>
        </div>

        <form action="{{ route('services.store') }}" method="POST" class="bg-white dark:bg-gray-800 p-10 rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-gray-700 transition-colors duration-300">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Désignation -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest">Désignation du Service</label>
                    <input type="text" name="nom" placeholder="Ex: Maintenance Informatique" required 
                        class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 font-bold text-sm text-slate-800 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-indigo-600 py-3 shadow-sm transition-colors">
                    @error('nom') <span class="text-red-500 text-[9px] font-bold uppercase">{{ $message }}</span> @enderror
                </div>

                <!-- Direction (Verrouillage automatique selon rôle) -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest">Direction de rattachement</label>
                    
                    @if(Auth::user()->role === 'admin_rh')
                        {{-- L'admin RH choisit la direction --}}
                        <select name="direction_id" required class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 font-bold text-sm text-slate-800 dark:text-gray-200 py-3 transition-colors focus:ring-blue-500">
                            <option value="" class="dark:bg-gray-800">-- Sélectionner la Direction --</option>
                            @foreach($directions as $direction)
                                <option value="{{ $direction->id }}" class="dark:bg-gray-800">{{ $direction->nom }}</option>
                            @endforeach
                        </select>
                    @else
                        {{-- Les autres admins sont verrouillés sur leur propre direction --}}
                        <div class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-200 dark:bg-gray-700/50 font-bold text-sm py-3 px-4 text-slate-500 dark:text-gray-400 cursor-not-allowed">
                            {{ Auth::user()->direction->nom ?? 'Ma Direction' }}
                        </div>
                        <input type="hidden" name="direction_id" value="{{ Auth::user()->direction_id }}">
                    @endif
                </div>
            </div>

            <!-- Missions -->
            <div class="mb-10 space-y-2">
                <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest">Missions & Attributions</label>
                <textarea name="missions" rows="5" placeholder="Décrivez brièvement les missions de ce service..."
                    class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 font-medium text-sm text-slate-800 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-indigo-600 shadow-sm p-4 transition-colors"></textarea>
            </div>

            <div class="flex items-center justify-end gap-6 pt-6 border-t border-gray-50 dark:border-gray-700">
                <a href="{{ route('services.index') }}" class="text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest hover:text-slate-600 dark:hover:text-gray-300 transition">
                    Annuler
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-indigo-900 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white px-10 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-blue-100 dark:shadow-none transition-all active:scale-95">
                    Confirmer la création
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
