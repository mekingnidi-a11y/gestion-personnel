<x-app-layout>
    @section('header-title', 'Mutation Interne')

    <div class="max-w-4xl mx-auto py-8 px-4 transition-colors duration-300">
        <div class="bg-white dark:bg-gray-800 shadow-2xl rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700">
            <!-- HEADER DU FORMULAIRE (ORANGE) -->
            <div class="bg-orange-600 dark:bg-orange-700 p-6 text-white flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-xl font-black uppercase tracking-tighter">Mutation Interne</h2>
                    <p class="text-orange-200 dark:text-orange-300 text-xs font-bold uppercase tracking-widest">Mouvement interne de personnel</p>
                </div>
                <div class="text-center md:text-right">
                    <span class="block text-[10px] font-bold text-orange-200 uppercase tracking-widest">Agent concerné</span>
                    <span class="text-lg font-black uppercase tracking-tight">{{ $agent->nom_complet }}</span>
                </div>
            </div>

            <form action="{{ route('agents.mutation.store', $agent->id) }}" method="POST" class="p-8 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Référence Acte -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Référence Acte *</label>
                        <input type="text" name="ref_acte_mutation" required value="{{ old('ref_acte_mutation') }}" 
                               placeholder="Ex: Note de service n°..."
                               class="w-full border-gray-200 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl py-3 px-4 focus:ring-orange-500 font-bold transition-all">
                    </div>

                    <!-- Date d'Effet -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Date d'Effet *</label>
                        <input type="date" name="date_mutation" required value="{{ old('date_mutation') }}" 
                               class="w-full border-gray-200 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl py-3 px-4 focus:ring-orange-500 font-bold transition-all">
                    </div>

                    <!-- Nouveau Service -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Nouveau Service *</label>
                        <select name="code_service" id="service_select" required 
                                class="w-full border-gray-200 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl py-3 px-4 focus:ring-orange-500 font-bold transition-all">
                            <option value="">-- Choisir le service --</option>
                            @foreach($services as $s)
                                <option value="{{ $s->id }}" {{ old('code_service') == $s->id ? 'selected' : '' }}>{{ $s->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nouvelle Fonction -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Nouvelle Fonction *</label>
                        <select name="code_fonction" id="fonction_select" required onchange="checkResponsibility()" 
                                class="w-full border-gray-200 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-xl py-3 px-4 focus:ring-orange-500 font-bold transition-all">
                            <option value="">-- Choisir la fonction --</option>
                            @foreach($fonctions as $f)
                                <option value="{{ $f->id }}" {{ old('code_fonction') == $f->id ? 'selected' : '' }}>{{ $f->intitule }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Bureau de responsabilité (Conditionnel) -->
                    <div id="bureau_group" class="hidden space-y-2 col-span-1 md:col-span-2 animate-fadeIn">
                        <label class="block text-[10px] font-black text-orange-600 dark:text-orange-500 uppercase tracking-widest italic">Bureau de responsabilité (Obligatoire pour Chef de Bureau) *</label>
                        <select name="code_bureau" id="bureau_select" 
                                class="w-full border-orange-200 dark:border-orange-900 bg-orange-50 dark:bg-orange-900/20 dark:text-orange-200 rounded-xl py-3 px-4 focus:ring-orange-500 font-bold transition-all">
                            <option value="">-- Choisir le bureau --</option>
                        </select>
                    </div>
                </div>

                <!-- ACTIONS ACTIONS FOOTER -->
                <div class="pt-8 border-t dark:border-gray-700 flex justify-between items-center">
                    <a href="{{ route('agents.mutation.index') }}" 
                       class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase hover:text-orange-600 dark:hover:text-orange-400 transition-colors tracking-widest">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="bg-orange-600 hover:bg-orange-700 text-white px-10 py-4 rounded-2xl font-black uppercase text-[11px] shadow-xl shadow-orange-100 dark:shadow-none transition-all transform active:scale-95 tracking-widest">
                        Valider la mutation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Chargement dynamique des bureaux
        document.getElementById('service_select').addEventListener('change', function() {
            const serviceId = this.value;
            const bureauSelect = document.getElementById('bureau_select');
            if(!serviceId) return;

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

        // Vérification automatique de la responsabilité
        function checkResponsibility() {
            const select = document.getElementById('fonction_select');
            const roleName = select.options[select.selectedIndex].text.toLowerCase();
            const wrapper = document.getElementById('bureau_group');
            const bureauInp = document.getElementById('bureau_select');

            if (roleName.includes('chef') && roleName.includes('bureau')) {
                wrapper.classList.remove('hidden');
                bureauInp.setAttribute('required', 'required');
            } else {
                wrapper.classList.add('hidden');
                bureauInp.removeAttribute('required');
                bureauInp.value = "";
            }
        }

        // Lancer la vérification au chargement (si old value)
        window.onload = checkResponsibility;
    </script>
</x-app-layout>
