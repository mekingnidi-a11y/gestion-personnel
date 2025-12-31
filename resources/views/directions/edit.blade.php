<x-app-layout>
    <x-slot name="header_title">Modifier la Direction</x-slot>

    <div class="max-w-4xl mx-auto">
        {{-- CORRECTION : Utilisation de $direction->id au lieu de $direction->code --}}
        <form action="{{ route('directions.update', $direction->id) }}" method="POST" class="bg-white p-10 rounded-[3rem] shadow-2xl border border-gray-100">
            @csrf
            @method('PATCH')

            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight mb-8">Édition de la structure</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Nom de la Direction</label>
                    <input type="text" name="nom" value="{{ $direction->nom }}" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Type</label>
                    {{-- SEUL ADMIN RH PEUT MODIFIER LE TYPE --}}
                    @if(Auth::user()->role === 'admin_rh')
                        <select name="type" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold text-sm">
                            <option value="generale" {{ $direction->type == 'generale' ? 'selected' : '' }}>Générale</option>
                            <option value="centrale" {{ $direction->type == 'centrale' ? 'selected' : '' }}>Centrale</option>
                            <option value="departementale" {{ $direction->type == 'departementale' ? 'selected' : '' }}>Départementale</option>
                            <option value="rattache_cabinet" {{ $direction->type == 'rattache_cabinet' ? 'selected' : '' }}>Rattachée Cabinet</option>
                        </select>
                    @else
                        <div class="w-full rounded-2xl border-gray-100 bg-gray-100 p-3 font-bold text-sm text-slate-500">
                            {{ ucfirst(str_replace('_', ' ', $direction->type)) }}
                            <input type="hidden" name="type" value="{{ $direction->type }}">
                        </div>
                    @endif
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Direction Parente</label>
                {{-- SEUL ADMIN RH PEUT MODIFIER LA HIÉRARCHIE --}}
                @if(Auth::user()->role === 'admin_rh')
                    <select name="code_direction_parent" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold text-sm">
                        <option value="">Aucune (Direction Racine)</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->code }}" {{ $direction->code_direction_parent == $parent->code ? 'selected' : '' }}>
                                {{ $parent->nom }} ({{ $parent->code }})
                            </option>
                        @endforeach
                    </select>
                @else
                    <div class="w-full rounded-2xl border-gray-100 bg-gray-100 p-3 font-bold text-sm text-slate-500">
                        {{ $direction->parent->nom ?? 'Racine' }}
                        <input type="hidden" name="code_direction_parent" value="{{ $direction->code_direction_parent }}">
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Email Contact</label>
                    <input type="email" name="contact_email" value="{{ $direction->contact_email }}" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Téléphone Contact</label>
                    <input type="text" name="contact_telephone" value="{{ $direction->contact_telephone }}" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold text-sm">
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Missions</label>
                <textarea name="missions" rows="5" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-medium text-sm">{{ $direction->missions }}</textarea>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('directions.index') }}" class="px-8 py-3 text-slate-400 font-black text-[10px] uppercase">Annuler</a>
                <button type="submit" class="bg-indigo-600 text-white px-10 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-indigo-200 transition-all active:scale-95">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
