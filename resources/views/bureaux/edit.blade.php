<x-app-layout>
    <x-slot name="header_title">Modifier le Bureau</x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('bureaux.index') }}" class="p-2 bg-white rounded-xl shadow-sm hover:bg-gray-50 transition">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-xl font-black text-slate-800 uppercase tracking-tight">Modification du Bureau</h1>
        </div>

{{-- Remplacez la ligne 12 par celle-ci --}}
<form action="{{ route('bureaux.update', ['bureau' => $bureau->id]) }}" method="POST" ...>
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Nom -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Nom du Bureau</label>
                    <input type="text" name="nom" value="{{ old('nom', $bureau->nom) }}" required
                        class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold text-sm focus:ring-blue-500 py-3">
                </div>

                <!-- Service (Lecture seule pour 2025) -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Service de rattachement</label>
                    <div class="w-full rounded-2xl border-gray-100 bg-gray-100 p-3 font-bold text-sm text-slate-500 cursor-not-allowed">
                        {{ $bureau->service->nom ?? 'N/A' }}
                    </div>
                    {{-- On ne permet pas de changer le service d'un bureau existant ici pour l'intégrité du code --}}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <!-- Localisation -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Localisation / Bureau N°</label>
                    <input type="text" name="localisation" value="{{ old('localisation', $bureau->localisation) }}"
                        class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold text-sm py-3">
                </div>

                <!-- Capacité -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Capacité (Postes)</label>
                    <input type="number" name="capacite" value="{{ old('capacite', $bureau->capacite) }}"
                        class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold text-sm py-3">
                </div>
            </div>

            <div class="flex items-center justify-end gap-6 pt-6 border-t border-gray-50">
                <a href="{{ route('bureaux.index') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Annuler</a>
                <button type="submit" class="bg-indigo-600 text-white px-10 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-indigo-100 transition-all active:scale-95">
                    Mettre à jour le bureau
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
