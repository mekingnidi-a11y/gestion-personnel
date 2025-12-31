<x-app-layout>
    @section('header-title', 'Enrôlement & Recrutement')

    <div class="max-w-6xl mx-auto py-8 px-4 transition-colors duration-300">
        
        <!-- AFFICHAGE DES ERREURS -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 rounded-xl text-xs font-bold shadow-sm">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('agents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- 1. ÉTAT CIVIL & IDENTITÉ -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="bg-indigo-900 dark:bg-indigo-950 p-4 px-6 text-white font-bold uppercase text-xs tracking-widest">
                    1. État Civil (Informations de base)
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-tight">Nom *</label>
                        <input type="text" name="nom" value="{{ old('nom') }}" required 
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-tight">Prénom *</label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}" required 
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-tight">Sexe *</label>
                        <select name="sexe" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl text-sm font-bold">
                            <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-tight">Date de Naissance *</label>
                        <input type="date" name="date_naissance" value="{{ old('date_naissance') }}" required 
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-tight">Lieu de Naissance</label>
                        <input type="text" name="lieu_naissance" value="{{ old('lieu_naissance') }}" 
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-tight">Nationalité</label>
                        <input type="text" name="nationalite" value="{{ old('nationalite', 'Congolaise') }}" 
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl text-sm font-bold">
                    </div>
                </div>
            </div>

            <!-- 2. DONNÉES D'ORIGINE (ARRÊTÉ DE RECRUTEMENT) -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="bg-indigo-700 dark:bg-slate-900 p-4 px-6 text-white font-bold uppercase text-xs tracking-widest border-b dark:border-slate-800">
                    2. Acte de Recrutement (DGARH)
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-tight">N° Arrêté / Décret *</label>
                        <input type="text" name="num_recrutement" value="{{ old('num_recrutement') }}" required 
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl text-sm font-bold">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-tight">Date de Signature *</label>
                        <input type="date" name="date_recrutement" value="{{ old('date_recrutement') }}" required 
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-tight">Grade Recrutement *</label>
                        <input type="text" name="grade_recrutement" value="{{ old('grade_recrutement') }}" required 
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-tight">Catégorie *</label>
                        <input type="text" name="categorie_recrutement" value="{{ old('categorie_recrutement') }}" required 
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-tight">Échelle</label>
                        <input type="text" name="echelle_recrutement" value="{{ old('echelle_recrutement') }}" 
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-tight">Échelon</label>
                        <input type="text" name="echelon_recrutement" value="{{ old('echelon_recrutement') }}" 
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl text-sm font-bold">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-tight">Diplôme de Recrutement *</label>
                        <input type="text" name="diplome_recrutement" value="{{ old('diplome_recrutement') }}" required 
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl text-sm font-bold">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-tight">Indice</label>
                        <input type="text" name="indice_recrutement" value="{{ old('indice_recrutement') }}" 
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl text-sm font-bold">
                    </div>
                </div>
            </div>

            <!-- 3. SWITCH AFFECTATION -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border-2 border-dashed border-indigo-200 dark:border-indigo-900/50">
                <label class="flex items-center cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="effectuer_affectation" value="on" id="toggleAffectation" class="sr-only peer" onchange="toggleSection()" {{ old('effectuer_affectation') ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-checked:bg-indigo-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                    </div>
                    <span class="ml-4 text-xs font-black text-indigo-900 dark:text-indigo-400 uppercase tracking-tight">Affecter l'agent immédiatement à une structure</span>
                </label>
            </div>

            <!-- 4. SECTION AFFECTATION -->
            <div id="sectionAffectation" class="{{ old('effectuer_affectation') ? '' : 'hidden' }} bg-indigo-50 dark:bg-indigo-900/10 shadow-lg rounded-2xl overflow-hidden border border-indigo-100 dark:border-indigo-900/30">
                <div class="bg-indigo-600 dark:bg-indigo-900 p-4 px-6 text-white font-bold uppercase text-xs tracking-widest">
                    Détails de l'Affectation Initiale
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-indigo-700 dark:text-indigo-400 uppercase tracking-tight">Direction de destination *</label>
                        <select name="direction_id" class="mt-1 block w-full border-indigo-200 dark:border-indigo-800 dark:bg-gray-900 dark:text-white rounded-xl focus:ring-indigo-500 text-sm font-bold">
                            <option value="">-- Choisir la structure --</option>
                            @foreach($directions as $direction)
                                <option value="{{ $direction->id }}" {{ old('direction_id') == $direction->id ? 'selected' : '' }}>
                                    {{ $direction->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-indigo-700 dark:text-indigo-400 uppercase tracking-tight">Référence Décision d'Affectation *</label>
                        <input type="text" name="ref_acte_affectation" value="{{ old('ref_acte_affectation') }}" placeholder="Note de service n°..." 
                               class="mt-1 block w-full border-indigo-200 dark:border-indigo-800 dark:bg-gray-900 dark:text-white rounded-xl text-sm font-bold">
                    </div>
                </div>
            </div>

            <!-- BOUTONS ACTIONS -->
            <div class="flex justify-end gap-4">
                <button type="reset" class="px-8 py-3 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 rounded-xl font-bold uppercase text-xs hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    Réinitialiser
                </button>
                <button type="submit" class="px-12 py-4 bg-indigo-600 text-white rounded-xl font-black shadow-xl shadow-indigo-200 dark:shadow-none hover:bg-indigo-700 transition transform active:scale-95 uppercase text-xs tracking-widest">
                    Valider l'Enregistrement
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleSection() {
            const section = document.getElementById('sectionAffectation');
            const checkbox = document.getElementById('toggleAffectation');
            if (checkbox.checked) {
                section.classList.remove('hidden');
                section.classList.add('animate-fadeIn'); // Optionnel : ajout d'une animation
            } else {
                section.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
