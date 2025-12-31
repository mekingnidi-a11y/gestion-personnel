<x-app-layout>
    @section('header-title', 'Nouveau Bureau')

    <div class="max-w-3xl mx-auto py-12 px-4 transition-colors duration-300">
        <form action="{{ route('bureaux.store') }}" method="POST" class="bg-white dark:bg-gray-800 p-10 rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-gray-700">
            @csrf
            <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase mb-8 tracking-tight">Créer un Bureau</h2>

            <div class="mb-6">
                <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase mb-2 tracking-widest">Nom du Bureau</label>
                <input type="text" name="nom" 
                       class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 font-bold text-sm py-3 text-slate-800 dark:text-white focus:ring-blue-500 focus:border-blue-500 transition-all" 
                       required placeholder="Ex: Bureau du Chef de Service">
            </div>

            <div class="mb-6">
                <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase mb-2 tracking-widest">Service de rattachement</label>
                <select name="service_id" 
                        class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 font-bold text-sm py-3 text-slate-800 dark:text-white focus:ring-blue-500 transition-all" 
                        required>
                    <option value="">-- Choisir un Service --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">
                            {{ $service->nom }} ({{ $service->direction->nom ?? 'Direction Inconnue' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-8">
                <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase mb-2 tracking-widest">Localisation / Bureau n°</label>
                <input type="text" name="localisation" 
                       class="w-full rounded-2xl border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 font-bold text-sm py-3 text-slate-800 dark:text-white focus:ring-blue-500 transition-all" 
                       placeholder="Ex: Bâtiment A, 2ème étage">
            </div>

            <div class="flex flex-col sm:flex-row justify-end items-center gap-4">
                <a href="{{ route('bureaux.index') }}" 
                   class="px-6 py-3 text-slate-400 dark:text-gray-500 font-black text-[10px] uppercase hover:text-slate-600 dark:hover:text-gray-300 transition-colors">
                    Annuler
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-10 py-3 rounded-2xl font-black text-[10px] uppercase shadow-lg shadow-blue-100 dark:shadow-none transition-all transform active:scale-95">
                    Enregistrer le bureau
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
