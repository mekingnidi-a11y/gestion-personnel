<div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-gray-100 p-8">
    <h3 class="text-orange-600 font-black uppercase text-sm mb-6 flex items-center">
        <span class="w-8 h-8 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mr-3">2</span>
        Gestion des Absences Justifiées & Permissions
    </h3>

    <form action="{{ route('pointages.absences.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-2xl border border-gray-100">
            <!-- Recherche Agent -->
            <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-gray-500 uppercase mb-2">Agent Concerné *</label>
                <select name="agent_id" required class="w-full border-gray-200 rounded-xl py-3 px-4 font-bold text-sm">
                    <option value="">-- Sélectionner l'agent --</option>
                    @foreach(\App\Models\Agent::where('est_synchronise', 1)->whereHas('affectationActuelle', fn($q) => $q->where('direction_id', auth()->user()->direction_id))->orderBy('nom')->get() as $agent)
                        <option value="{{ $agent->id }}">{{ $agent->nom_complet }} ({{ $agent->matricule ?? 'Sans matricule' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-gray-500 uppercase">Type d'absence *</label>
                <select name="statut" required class="w-full border-gray-200 rounded-xl py-3 px-4">
                    <option value="permission">Permission</option>
                    <option value="justifie">Maladie / Justifié</option>
                    <option value="conge">Congé</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-gray-500 uppercase">Date de début *</label>
                <input type="date" name="date_debut" required class="w-full border-gray-200 rounded-xl py-3 px-4">
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-gray-500 uppercase">Date de fin (Inclus) *</label>
                <input type="date" name="date_fin" required class="w-full border-gray-200 rounded-xl py-3 px-4">
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-black text-gray-500 uppercase">Motif / Commentaire</label>
                <input type="text" name="motif" placeholder="Ex: Autorisation N°..." class="w-full border-gray-200 rounded-xl py-3 px-4">
            </div>
        </div>

     <div class="md:col-span-2 space-y-2">
        <label class="block text-[10px] font-black text-orange-600 uppercase tracking-widest">Pièce Justificative (Facultatif)</label>
        <div class="relative border-2 border-dashed border-orange-100 rounded-2xl p-4 bg-orange-50/50 hover:bg-orange-50 transition">
            <input type="file" name="document" 
                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
            <div class="text-center">
                <svg class="w-8 h-8 text-orange-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <p class="text-[9px] font-bold text-gray-500 uppercase">Cliquez ou glissez un document (PDF, Image, Doc)</p>
            </div>
        </div>
    </div>

    <div class="flex justify-end pt-4">
        <button type="submit" class="bg-orange-600 text-white px-10 py-4 rounded-2xl font-black uppercase text-xs shadow-xl shadow-orange-100">
            Enregistrer l'absence
        </button>
    </div>
</form>
</div>
