<x-app-layout>
    <x-slot name="header_title">Modifier la Direction</x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4 transition-colors duration-300">
        {{-- ACTION : Utilisation de l'ID UUID --}}
        <form action="{{ route('directions.update', $direction->id) }}" method="POST" class="bg-white dark:bg-gray-800 p-10 rounded-[3rem] shadow-2xl border border-gray-100 dark:border-gray-700 transition-colors duration-300">
            @csrf
            @method('PATCH')

            <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight mb-8">Édition de la structure</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nom de la Direction -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest">Nom de la Direction</label>
                    <input type="text" name="nom" value="{{ $direction->nom }}" 
                        class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 font-bold text-sm text-slate-800 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-600 transition-colors py-3 shadow-sm">
                </div>
                
                <!-- Type -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest">Type</label>
                    {{-- SEUL ADMIN RH PEUT MODIFIER LE TYPE --}}
                    @if(Auth::user()->role === 'admin_rh')
                        <select name="type" class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 font-bold text-sm text-slate-800 dark:text-gray-200 py-3 transition-colors focus:ring-indigo-500">
                            <option value="generale" {{ $direction->type == 'generale' ? 'selected' : '' }} class="dark:bg-gray-800">Générale</option>
                            <option value="centrale" {{ $direction->type == 'centrale' ? 'selected' : '' }} class="dark:bg-gray-800">Centrale</option>
                            <option value="departementale" {{ $direction->type == 'departementale' ? 'selected' : '' }} class="dark:bg-gray-800">Départementale</option>
                            <option value="rattache_cabinet" {{ $direction->type == 'rattache_cabinet' ? 'selected' : '' }} class="dark:bg-gray-800">Rattachée Cabinet</option>
                        </select>
                    @else
                        <div class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-100 dark:bg-gray-700/50 p-3 font-bold text-sm text-slate-500 dark:text-gray-400 cursor-not-allowed">
                            {{ ucfirst(str_replace('_', ' ', $direction->type)) }}
                            <input type="hidden" name="type" value="{{ $direction->type }}">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Direction Parente -->
            <div class="mb-6 space-y-2">
                <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest">Direction Parente</label>
                {{-- SEUL ADMIN RH PEUT MODIFIER LA HIÉRARCHIE --}}
                @if(Auth::user()->role === 'admin_rh')
                    <select name="code_direction_parent" class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 font-bold text-sm text-slate-800 dark:text-gray-200 py-3 transition-colors focus:ring-indigo-500">
                        <option value="" class="dark:bg-gray-800">Aucune (Direction Racine)</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->code }}" {{ $direction->code_direction_parent == $parent->code ? 'selected' : '' }} class="dark:bg-gray-800">
                                {{ $parent->nom }} ({{ $parent->code }})
                            </option>
                        @endforeach
                    </select>
                @else
                    <div class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-100 dark:bg-gray-700/50 p-3 font-bold text-sm text-slate-500 dark:text-gray-400 cursor-not-allowed">
                        {{ $direction->parent->nom ?? 'Racine' }}
                        <input type="hidden" name="code_direction_parent" value="{{ $direction->code_direction_parent }}">
                    </div>
                @endif
            </div>

            <!-- Contacts Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest">Email Contact</label>
                    <input type="email" name="contact_email" value="{{ $direction->contact_email }}" 
                        class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 font-bold text-sm text-slate-800 dark:text-gray-200 py-3 shadow-sm transition-colors">
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest">Téléphone Contact</label>
                    <input type="text" name="contact_telephone" value="{{ $direction->contact_telephone }}" 
                        class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 font-bold text-sm text-slate-800 dark:text-gray-200 py-3 shadow-sm transition-colors">
                </div>
            </div>

            <!-- Missions -->
            <div class="mb-8 space-y-2">
                <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest">Missions</label>
                <textarea name="missions" rows="5" 
                    class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 font-medium text-sm text-slate-800 dark:text-gray-200 p-4 transition-colors focus:ring-indigo-600 focus:border-indigo-600">{{ $direction->missions }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-6 pt-4 items-center">
                <a href="{{ route('directions.index') }}" class="text-slate-400 dark:text-gray-500 font-black text-[10px] uppercase tracking-widest hover:text-slate-600 dark:hover:text-gray-300 transition">
                    Annuler
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white px-10 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-indigo-100 dark:shadow-none transition-all active:scale-95">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
