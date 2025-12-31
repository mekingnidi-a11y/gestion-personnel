<x-app-layout>
    <div class="max-w-4xl mx-auto py-12">
        <form action="{{ route('fonctions.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">
                <div class="bg-indigo-900 p-6 text-white flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-black uppercase tracking-tighter">Nouveau Poste / Fonction</h2>
                        <p class="text-indigo-300 text-[10px] uppercase font-bold">Référentiel des structures 2025</p>
                    </div>
                    <i class="fas fa-briefcase text-2xl text-indigo-400"></i>
                </div>
                
                <div class="p-8 space-y-8">
                    <!-- Direction -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-indigo-900 uppercase tracking-widest">Structure de rattachement (Direction) *</label>
                        <select name="code_direction" required class="w-full border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 transition-all py-3 px-4">
                            <option value="">-- Choisir la direction --</option>
                            @foreach($directions as $dir)
                                <option value="{{ $dir->code }}">{{ $dir->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Intitulé -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-indigo-900 uppercase tracking-widest">Libellé exact de la fonction *</label>
                        <input type="text" name="intitule" placeholder="Ex: Chef de Bureau Maintenance Informatique" required 
                               class="w-full border-gray-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 transition-all py-3 px-4">
                    </div>

                    <!-- Options Avancées -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center p-5 bg-amber-50 rounded-3xl border border-amber-100 group hover:bg-amber-100 transition-all cursor-pointer">
                            <input type="checkbox" name="est_responsabilite" value="1" id="resp" 
                                   class="w-6 h-6 rounded-lg text-amber-600 border-amber-300 focus:ring-amber-500 transition">
                            <label for="resp" class="ml-4 cursor-pointer">
                                <span class="block text-xs font-black text-amber-900 uppercase">Poste de Commandement</span>
                                <span class="text-[10px] text-amber-700 font-medium italic">Cochez si c'est un Chef de Service ou de Bureau.</span>
                            </label>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-indigo-900 uppercase tracking-widest">Niveau Hiérarchique</label>
                            <input type="number" name="niveau_hierarchique" value="0" 
                                   class="w-full border-gray-200 rounded-2xl py-3 px-4 focus:ring-indigo-100">
                        </div>
                    </div>
                </div>
                
                <div class="p-8 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                    <a href="{{ route('fonctions.index') }}" class="text-xs font-black text-gray-400 uppercase hover:text-gray-600 transition">Annuler</a>
                    <button type="submit" class="bg-indigo-600 text-white px-10 py-4 rounded-2xl font-black uppercase text-xs shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-1 transition-all">
                        Valider et Enregistrer
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
