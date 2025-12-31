<x-app-layout>
    <x-slot name="header_title">Modifier le Service</x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4 transition-colors duration-300">
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('services.index') }}" class="p-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 border border-transparent dark:border-gray-700 transition">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Modification du Service</h1>
        </div>

        {{-- ACTION : Utilisation de l'ID UUID --}}
        <form action="{{ route('services.update', $service->id) }}" method="POST" class="bg-white dark:bg-gray-800 p-10 rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-gray-700 transition-colors duration-300">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Nom du Service -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest">Désignation du Service</label>
                    <input type="text" name="nom" value="{{ old('nom', $service->nom) }}" 
                        class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 font-bold text-sm text-slate-800 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-indigo-600 py-3 shadow-sm transition-colors">
                </div>

                <!-- Direction (Utilisation de direction_id) -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest">Direction de rattachement</label>
                    @if(Auth::user()->role === 'admin_rh')
                        <select name="direction_id" class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 font-bold text-sm text-slate-800 dark:text-gray-200 py-3 transition-colors focus:ring-blue-500">
                            @foreach($directions as $direction)
                                <option value="{{ $direction->id }}" {{ $service->direction_id == $direction->id ? 'selected' : '' }} class="dark:bg-gray-800">
                                    {{ $direction->nom }} ({{ $direction->code }})
                                </option>
                            @endforeach
                        </select>
                    @else
                        {{-- Verrouillé pour les admins locaux --}}
                        <div class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-200 dark:bg-gray-700/50 font-bold text-sm py-3 px-4 text-slate-500 dark:text-gray-400 cursor-not-allowed transition-colors">
                            {{ $service->direction->nom }}
                        </div>
                        <input type="hidden" name="direction_id" value="{{ $service->direction_id }}">
                    @endif
                </div>
            </div>

            <!-- Missions -->
            <div class="mb-10 space-y-2">
                <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest">Missions & Attributions du Service</label>
                <textarea name="missions" rows="6" 
                    class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 font-medium text-sm text-slate-800 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-indigo-600 shadow-sm p-4 transition-colors">{{ old('missions', $service->missions) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-6 pt-6 border-t border-gray-50 dark:border-gray-700">
                <a href="{{ route('services.index') }}" class="text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest hover:text-slate-600 dark:hover:text-gray-300 transition">
                    Annuler
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-indigo-900 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white px-10 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-blue-100 dark:shadow-none transition-all active:scale-95">
                    Enregistrer les modifications
                </button>
            </div>
        </form>

        <div class="mt-8 p-6 bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-100 dark:border-blue-900/30 flex items-start gap-4 transition-colors">
            <svg class="w-5 h-5 text-blue-500 dark:text-blue-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-[10px] text-blue-700 dark:text-blue-300 font-bold uppercase leading-relaxed">
                Information technique : Ce service est lié à l'ID stable <span class="font-mono">{{ $service->id }}</span>. 
                Toute modification du nom ou de la direction mettra à jour automatiquement le code métier (<span class="font-mono">{{ $service->code }}</span>).
            </p>
        </div>
    </div>
</x-app-layout>
