@if ($errors->any())
    <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@section('header-title', 'Nouveau Recrutement')

@section('header-title', 'Enrôlement & Recrutement')

<x-app-layout>
    <div class="max-w-6xl mx-auto py-8">
        <form action="{{ route('agents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- 1. ÉTAT CIVIL & IDENTITÉ -->
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                <div class="bg-indigo-900 p-4 px-6 text-white font-bold uppercase text-xs tracking-widest">
                    1. État Civil (Informations de base)
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Nom *</label>
                        <input type="text" name="nom" required class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Prénom *</label>
                        <input type="text" name="prenom" required class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Sexe *</label>
                        <select name="sexe" required class="mt-1 block w-full border-gray-300 rounded-xl">
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Date de Naissance *</label>
                        <input type="date" name="date_naissance" required class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Lieu de Naissance</label>
                        <input type="text" name="lieu_naissance" class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Nationalité</label>
                        <input type="text" name="nationalite" value="Congolaise" class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                </div>
            </div>

            <!-- 2. DONNÉES D'ORIGINE (ARRÊTÉ DE RECRUTEMENT) -->
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                <div class="bg-indigo-700 p-4 px-6 text-white font-bold uppercase text-xs tracking-widest">
                    2. Acte de Recrutement (DGARH)
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">N° Arrêté / Décret *</label>
                        <input type="text" name="num_recrutement" required class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Date de Signature *</label>
                        <input type="date" name="date_recrutement" required class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Grade Recrutement *</label>
                        <input type="text" name="grade_recrutement" required class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Catégorie *</label>
                        <input type="text" name="categorie_recrutement" required class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Échelle</label>
                        <input type="text" name="echelle_recrutement" class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Échelon</label>
                        <input type="text" name="echelon_recrutement" class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Indice</label>
                        <input type="text" name="indice_recrutement" class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Diplôme de Recrutement *</label>
                        <input type="text" name="diplome_recrutement" required class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>

                      <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Établissement de Recrutement</label>
                        <input type="text" name="etablissement_recrutement" class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                </div>
            </div>

         <!-- ... (Haut du formulaire identique) ... -->

<!-- 3. SWITCH AFFECTATION -->
<div class="bg-white p-5 rounded-2xl shadow-sm border-2 border-dashed border-indigo-200">
    <label class="flex items-center cursor-pointer">
        <div class="relative">
            <!-- Ajout de value="on" pour garantir la détection par required_if -->
            <input type="checkbox" name="effectuer_affectation" value="on" id="toggleAffectation" class="sr-only peer" onchange="toggleSection()" {{ old('effectuer_affectation') ? 'checked' : '' }}>
            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-indigo-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
        </div>
        <span class="ml-4 text-sm font-black text-indigo-900 uppercase">Affecter l'agent immédiatement à une structure</span>
    </label>
</div>

<!-- 4. SECTION AFFECTATION -->
<div id="sectionAffectation" class="{{ old('effectuer_affectation') ? '' : 'hidden' }} bg-indigo-50 shadow-lg rounded-2xl overflow-hidden border border-indigo-100">
    <div class="bg-indigo-600 p-4 px-6 text-white font-bold uppercase text-xs tracking-widest">
        Détails de l'Affectation Initiale
    </div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-bold text-indigo-700 uppercase">Direction de destination *</label>
            <!-- Changement de code_direction vers direction_id -->
            <select name="direction_id" class="mt-1 block w-full border-indigo-200 rounded-xl focus:ring-indigo-500">
                <option value="">-- Choisir la structure --</option>
                @foreach($directions as $direction)
                    <option value="{{ $direction->id }}" {{ old('direction_id') == $direction->id ? 'selected' : '' }}>
                        {{ $direction->nom }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-indigo-700 uppercase">Référence Décision d'Affectation *</label>
            <input type="text" name="ref_acte_affectation" value="{{ old('ref_acte_affectation') }}" placeholder="Note de service n°..." class="mt-1 block w-full border-indigo-200 rounded-xl">
        </div>
    </div>
</div>

<!-- ... (Boutons et Script identiques) ... -->

            <!-- BOUTONS -->
            <div class="flex justify-end gap-4">
                <button type="reset" class="px-8 py-3 bg-gray-100 text-gray-500 rounded-xl font-bold uppercase text-xs hover:bg-gray-200 transition">Annuler</button>
                <button type="submit" class="px-12 py-4 bg-indigo-600 text-white rounded-xl font-black shadow-xl hover:bg-indigo-700 transition transform active:scale-95 uppercase text-xs">
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
            } else {
                section.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>

