<x-app-layout>
    @section('header-title', 'Installation de l\'Agent')

    <div class="max-w-4xl mx-auto py-8">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-xl">
                <ul class="list-disc ml-5 text-[10px] font-black uppercase">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">
            <div class="bg-indigo-900 p-6 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-black uppercase tracking-tighter">Finalisation Locale</h2>
                    <p class="text-indigo-300 text-[10px] font-bold uppercase">Affectation à un service et une fonction</p>
                </div>
                <div class="text-right">
                    <span class="block text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Agent</span>
                    <span class="text-lg font-black uppercase">{{ $agent->nom_complet }}</span>
                </div>
            </div>

            <form action="{{ route('agents.prise-service.store', $agent->id) }}" method="POST" class="p-8 space-y-6">
                @csrf

                <!-- Matricule (Optionnel à cette étape) -->
                <div class="bg-indigo-50 p-5 rounded-2xl border border-indigo-100">
                    <label class="block text-[10px] font-black text-indigo-900 uppercase mb-2">Matricule (Si déjà disponible)</label>
                    <input type="text" name="matricule" value="{{ old('matricule', $agent->matricule) }}" 
                           class="w-full border-gray-200 rounded-xl py-3 px-4 font-bold text-indigo-900 focus:ring-indigo-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date de prise de service -->
                    <div class="space-y-1">
                        <label class="block text-[10px] font-black text-gray-500 uppercase">Date Prise de Service Effective *</label>
                        <input type="date" name="date_premiere_prise_service" required class="w-full border-gray-200 rounded-xl py-3 px-4 focus:ring-indigo-500">
                    </div>

                    <!-- Sélection Fonction -->
                    <div class="space-y-1">
                        <label class="block text-[10px] font-black text-gray-500 uppercase">Fonction Référencée *</label>
                        <select name="code_fonction" id="fonction_select" required onchange="handleRoleChange()" class="w-full border-gray-200 rounded-xl py-3 px-4 focus:ring-indigo-500">
                            <option value="">-- Choisir la fonction --</option>
                            @foreach($fonctions as $f)
                                <option value="{{ $f->id }}" data-type="{{ strtolower($f->intitule) }}">{{ $f->intitule }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sélection Service -->
                    <div class="space-y-1">
                        <label class="block text-[10px] font-black text-gray-500 uppercase">Service d'Affectation *</label>
                        <select name="code_service" id="service_select" required class="w-full border-gray-200 rounded-xl py-3 px-4 focus:ring-indigo-500">
                            <option value="">-- Choisir le service --</option>
                            @foreach($services as $s)
                                <option value="{{ $s->id }}">{{ $s->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sélection Bureau (Caché si pas Chef de bureau) -->
                    <div id="bureau_wrapper" class="space-y-1 hidden col-span-1 md:col-span-1">
                        <label class="block text-[10px] font-black text-amber-600 uppercase italic">Unité / Bureau de Responsabilité *</label>
                        <select name="code_bureau" id="bureau_select" class="w-full border-amber-200 bg-amber-50 rounded-xl py-3 px-4 focus:ring-amber-500">
                            <option value="">-- Choisir le bureau --</option>
                        </select>
                    </div>
                </div>

                <div class="pt-8 border-t border-gray-100 flex justify-between items-center">
                    <a href="{{ route('prises-service.index') }}" class="text-[10px] font-black text-gray-400 uppercase hover:text-gray-600">Retour</a>
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-4 rounded-2xl font-black uppercase text-[11px] shadow-xl hover:bg-indigo-700 transition transform active:scale-95">
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
