<x-app-layout>
    @section('header-title', 'Mutation Interne')

    <div class="max-w-4xl mx-auto py-8">
        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">
            <div class="bg-orange-600 p-6 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-black uppercase tracking-tighter">Mutation Interne</h2>
                    <p class="text-orange-200 text-xs font-bold uppercase">Mouvement interne de personnel</p>
                </div>
                <div class="text-right">
                    <span class="text-lg font-black uppercase">{{ $agent->nom_complet }}</span>
                </div>
            </div>

            <form action="{{ route('agents.mutation.store', $agent->id) }}" method="POST" class="p-8 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Référence Acte *</label>
                        <input type="text" name="ref_acte_mutation" required value="{{ old('ref_acte_mutation') }}" class="w-full border-gray-200 rounded-xl py-3 px-4 focus:ring-orange-500">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Date d'Effet *</label>
                        <input type="date" name="date_mutation" required value="{{ old('date_mutation') }}" class="w-full border-gray-200 rounded-xl py-3 px-4">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Nouveau Service *</label>
                        <select name="code_service" id="service_select" required class="w-full border-gray-200 rounded-xl py-3 px-4">
                            <option value="">-- Choisir le service --</option>
                            @foreach($services as $s)
                                <option value="{{ $s->id }}" {{ old('code_service') == $s->id ? 'selected' : '' }}>{{ $s->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">Nouvelle Fonction *</label>
                        <select name="code_fonction" id="fonction_select" required onchange="checkResponsibility()" class="w-full border-gray-200 rounded-xl py-3 px-4">
                            <option value="">-- Choisir la fonction --</option>
                            @foreach($fonctions as $f)
                                <option value="{{ $f->id }}" {{ old('code_fonction') == $f->id ? 'selected' : '' }}>{{ $f->intitule }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="bureau_group" class="hidden space-y-2 col-span-2">
                        <label class="block text-xs font-black text-orange-600 uppercase tracking-widest">Bureau de responsabilité *</label>
                        <select name="code_bureau" id="bureau_select" class="w-full border-orange-200 bg-orange-50 rounded-xl py-3 px-4">
                            <option value="">-- Choisir le bureau --</option>
                        </select>
                    </div>
                </div>

                <div class="pt-8 border-t flex justify-end space-x-4">
                    <a href="{{ route('agents.mutation.index') }}" class="text-xs font-black text-gray-400 uppercase py-4">Annuler</a>
                    <button type="submit" class="bg-orange-600 text-white px-10 py-4 rounded-2xl font-black uppercase text-xs shadow-xl hover:bg-orange-700 transition-all">
                        Valider la mutation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('service_select').addEventListener('change', function() {
            const serviceId = this.value;
            const bureauSelect = document.getElementById('bureau_select');
            if(!serviceId) return;

            fetch(`/api/services/${serviceId}/bureaux`)
                .then(r => r.json())
                .then(data => {
                    bureauSelect.innerHTML = '<option value="">-- Choisir le bureau --</option>';
                    data.forEach(b => {
                        bureauSelect.innerHTML += `<option value="${b.id}">${b.nom}</option>`;
                    });
                });
        });

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
    </script>
</x-app-layout>
