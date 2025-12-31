<x-app-layout>
    @section('header-title', 'Modifier le Recrutement')

    <div class="max-w-6xl mx-auto py-8 px-4">
        <!-- Affichage des erreurs de validation -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('agents.update', $agent->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- 1. ÉTAT CIVIL -->
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                <div class="bg-indigo-900 p-4 px-6 text-white font-bold uppercase text-xs tracking-widest">
                    1. État Civil (Modification)
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Nom *</label>
                        <input type="text" name="nom" value="{{ old('nom', $agent->nom) }}" required class="mt-1 block w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Prénom *</label>
                        <input type="text" name="prenom" value="{{ old('prenom', $agent->prenom) }}" required class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Sexe *</label>
                        <select name="sexe" required class="mt-1 block w-full border-gray-300 rounded-xl">
                            <option value="M" {{ (old('sexe', $agent->sexe) == 'M') ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ (old('sexe', $agent->sexe) == 'F') ? 'selected' : '' }}>Féminin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Date de Naissance *</label>
                        <input type="date" name="date_naissance" value="{{ old('date_naissance', $agent->date_naissance) }}" required class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Lieu de Naissance</label>
                        <input type="text" name="lieu_naissance" value="{{ old('lieu_naissance', $agent->lieu_naissance) }}" class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Nationalité</label>
                        <input type="text" name="nationalite" value="{{ old('nationalite', $agent->nationalite) }}" class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                </div>
            </div>

            <!-- 2. DONNÉES DE RECRUTEMENT -->
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                <div class="bg-indigo-700 p-4 px-6 text-white font-bold uppercase text-xs tracking-widest">
                    2. Acte de Recrutement (Correction des données)
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">N° Arrêté / Décret *</label>
                        <input type="text" name="num_recrutement" value="{{ old('num_recrutement', $agent->num_recrutement) }}" required class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Date de Signature *</label>
                        <input type="date" name="date_recrutement" value="{{ old('date_recrutement', $agent->date_recrutement) }}" required class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Grade *</label>
                        <input type="text" name="grade_recrutement" value="{{ old('grade_recrutement', $agent->grade_recrutement) }}" required class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Catégorie *</label>
                        <input type="text" name="categorie_recrutement" value="{{ old('categorie_recrutement', $agent->categorie_recrutement) }}" required class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Échelle</label>
                        <input type="text" name="echelle_recrutement" value="{{ old('echelle_recrutement', $agent->echelle_recrutement) }}" class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Échelon</label>
                        <input type="text" name="echelon_recrutement" value="{{ old('echelon_recrutement', $agent->echelon_recrutement) }}" class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Indice</label>
                        <input type="text" name="indice_recrutement" value="{{ old('indice_recrutement', $agent->indice_recrutement) }}" class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Diplôme de Recrutement *</label>
                        <input type="text" name="diplome_recrutement" value="{{ old('diplome_recrutement', $agent->diplome_recrutement) }}" required class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Établissement</label>
                        <input type="text" name="etablissement_recrutement" value="{{ old('etablissement_recrutement', $agent->etablissement_recrutement) }}" class="mt-1 block w-full border-gray-300 rounded-xl">
                    </div>
                </div>
            </div>

            <!-- BOUTONS -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('agents.index') }}" class="px-8 py-3 bg-gray-100 text-gray-500 rounded-xl font-bold uppercase text-xs hover:bg-gray-200 transition text-center">
                    Annuler
                </a>
                <button type="submit" class="px-12 py-4 bg-indigo-600 text-white rounded-xl font-black shadow-xl hover:bg-indigo-700 transition transform active:scale-95 uppercase text-xs">
                    Mettre à jour l'enrôlement
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
