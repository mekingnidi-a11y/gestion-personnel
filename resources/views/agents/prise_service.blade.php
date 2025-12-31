<x-app-layout>
    @section('header-title', 'Installation de l\'Agent')

    <div class="max-w-4xl mx-auto py-8 px-4 transition-colors duration-300">
        <!-- ERREURS DE VALIDATION -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 rounded-xl shadow-sm">
                <ul class="list-disc ml-5 text-[10px] font-black uppercase tracking-tight">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-colors duration-300">
            <!-- HEADER DU FORMULAIRE -->
            <div class="bg-indigo-900 dark:bg-indigo-950 p-6 text-white flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-xl font-black uppercase tracking-tighter">Finalisation Locale</h2>
                    <p class="text-indigo-300 dark:text-indigo-400 text-[10px] font-bold uppercase tracking-widest">Affectation à un service et une fonction</p>
                </div>
                <div class="text-center md:text-right">
                    <span class="block text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Agent à installer</span>
                    <span class="text-lg font-black uppercase tracking-tight">{{ $agent->nom_complet }}</span>
                </div>
            </div>

            <form action="{{ route('agents.prise-service.store', $agent->id) }}" method="POST" class="p-8 space-y-6">
                @csrf

                <!-- SECTION MATRICULE -->
                <div class="bg-indigo-50 dark:bg-indigo-900/20 p-5 rounded-2xl border border-indigo-100 dark:border-indigo-900/40">
                    <label class="block text-[10px] font-black text-indigo-900 dark:text-indigo-400 uppercase mb-2 tracking-widest">Matricule (Si déjà disponible)</label>
                    <input type="text" name="matricule" value="{{ old('matricule', $agent->matricule) }}" 
                           placeholder="Ex: 784512X"
                           class="w-full border-gray-200 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl py-3 px-4 font-bold text-indigo-900 focus:ring-indigo-500 transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- DATE PRISE DE SERVICE -->
                    <div class="space-y-1">
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Date Prise de Service Effective *</label>
                        <input type="date" name="date_premiere_prise_service" required 
                               class="w-full border-gray-200 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl py-3 px-4 focus:ring-indigo-500 font-bold transition-all">
                    </div>

                    <!-- SELECTION FONCTION -->
                    <div class="space-y-1">
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Fonction Référencée *</label>
                        <select name="code_fonction" id="fonction_select" required onchange="handleRoleChange()" 
                                class="w-full border-gray-200 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl py-3 px-4 focus:ring-indigo-500 font-bold transition-all">
                            <option value="">-- Choisir la fonction --</option>
                            @foreach($fonctions as $f)
                                <option value="{{ $f->id }}" data-type="{{ strtolower($f->intitule) }}">{{ $f->intitule }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- SELECTION SERVICE -->
                    <div class="space-y-1">
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Service d'Affectation *</label>
                        <select name="code_service" id="service_select" required 
                                class="w-full border-gray-200 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl py-3 px-4 focus:ring-indigo-500 font-bold transition-all">
                            <option value="">-- Choisir le service --</option>
                            @foreach($services as $s)
                                <option value="{{ $s->id }}">{{ $s->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- SELECTION BUREAU (DYNAMIQUE) -->
                    <div id="bureau_wrapper" class="space-y-1 hidden animate-fadeIn">
                        <label class="block text-[10px] font-black text-amber-600 dark:text-amber-500 uppercase italic tracking-widest">Unité / Bureau de Responsabilité *</label>
                        <select name="code_bureau" id="bureau_select" 
                                class="w-full border-amber-200 dark:border-amber-900 bg-amber-50 dark:bg-amber-900/20 dark:text-amber-200 rounded-xl py-3 px-4 focus:ring-amber-500 font-bold transition-all">
                            <option value="">-- Choisir le bureau --</option>
                        </select>
                    </div>
                </div>

                <!-- ACTIONS FOOTER -->
                <div class="pt-8 border-t border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">
                    <a href="{{ route('prises-service.index') }}" 
                       class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors tracking-widest">
                        &larr; Retour à la liste
                    </a>
                    <button type="submit" 
                            class="w-full md:w-auto bg-indigo-600 text-white px-10 py-4 rounded-2xl font-black uppercase text-[11px] shadow-xl shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition transform active:scale-95 tracking-widest">
                        Confirmer l'installation locale
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Filtrage des bureaux par service sélectionné
        document.getElementById('service_select').addEventListener('change', function() {
            const serviceId = this.value;
            const bureauSelect = document.getElementById('bureau_select');
            bureauSelect.innerHTML = '<option value="">Chargement...</option>';

            fetch(`/api/services/${serviceId}/bureaux`)
                .then(r => r.json())
                .then(data => {
                    bureauSelect.innerHTML = '<option value="">-- Choisir le bureau --</option>';
                    data.forEach(b => {
                        bureauSelect.innerHTML += `<option value="${b.id}">${b.nom}</option>`;
                    });
                });
        });

        // Gestion de l'affichage du champ Bureau selon la fonction (Chef de Bureau)
        function handleRoleChange() {
            const select = document.getElementById('fonction_select');
            const roleName = select.options[select.selectedIndex].text.toLowerCase();
            const wrapper = document.getElementById('bureau_wrapper');
            const bureauInp = document.getElementById('bureau_select');

            // Si le rôle contient "chef" et "bureau", on affiche le champ
            if (roleName.includes('chef') && roleName.includes('bureau')) {
                wrapper.classList.remove('hidden');
                bureauInp.setAttribute('required', 'required');
            } else {
                wrapper.classList.add('hidden');
                bureauInp.removeAttribute('required');
                bureauInp.value = "";
            }
        }
    </script>
</x-app-layout>
