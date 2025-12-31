<x-app-layout>
    <x-slot name="header_title">Nouveau Bureau</x-slot>

    <div class="max-w-3xl mx-auto py-12">
        <form action="{{ route('bureaux.store') }}" method="POST" class="bg-white p-10 rounded-[2.5rem] shadow-2xl border border-gray-100">
            @csrf
            <h2 class="text-xl font-black text-slate-800 uppercase mb-8">Créer un Bureau</h2>

            <div class="mb-6">
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Nom du Bureau</label>
                <input type="text" name="nom" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold text-sm py-3" required placeholder="Ex: Bureau du Chef de Service">
            </div>

            <div class="mb-6">
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Service de rattachement</label>
                <select name="service_id" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold text-sm py-3" required>
                    <option value="">-- Choisir un Service --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">
                            {{ $service->nom }} ({{ $service->direction->nom ?? 'Direction Inconnue' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-8">
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Localisation / Bureau n°</label>
                <input type="text" name="localisation" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold text-sm py-3" placeholder="Ex: Bâtiment A, 2ème étage">
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('bureaux.index') }}" class="px-6 py-3 text-slate-400 font-black text-[10px] uppercase">Annuler</a>
                <button type="submit" class="bg-blue-600 text-white px-10 py-3 rounded-2xl font-black text-[10px] uppercase shadow-lg shadow-blue-100">
                    Enregistrer le bureau
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
