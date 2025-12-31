<x-app-layout>
    <x-slot name="header-title">Validation de l'Agent</x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl rounded-[2rem] p-10 border border-gray-100">
                <h2 class="text-2xl font-black text-slate-800 uppercase mb-6">Configurer & Valider l'accès</h2>
                
                <form method="POST" action="{{ route('users.confirm-validation', $user->id) }}">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Username -->
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase mb-2">Identifiant (Username)</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full px-5 py-3 bg-gray-50 border-transparent rounded-2xl focus:ring-4 focus:ring-blue-500/5 font-bold text-slate-700">
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase mb-2">Email Professionnel</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-5 py-3 bg-gray-50 border-transparent rounded-2xl focus:ring-4 focus:ring-blue-500/5 font-bold text-slate-700">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Direction (CORRIGÉ : direction_id au lieu de code_direction) -->
                    <div class="mb-8">
                        <label class="block text-xs font-black text-slate-400 uppercase mb-2">Direction d'affectation</label>
                        <select name="direction_id" class="w-full px-5 py-3 bg-gray-50 border-transparent rounded-2xl font-bold text-slate-700 shadow-inner">
                            @foreach($directions as $direction)
                                {{-- On utilise direction->id (UUID) --}}
                                <option value="{{ $direction->id }}" {{ (old('direction_id', $user->direction_id) == $direction->id) ? 'selected' : '' }}>
                                    {{ $direction->nom }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('direction_id')" class="mt-2" />
                    </div>

                    <!-- ROLE -->
                    <div class="bg-blue-50 p-6 rounded-[1.5rem] border border-blue-100 mb-8">
                        <label class="block text-xs font-black text-blue-600 uppercase mb-4 tracking-widest">Attribuer un Rôle Système</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="flex items-center p-4 bg-white rounded-xl cursor-pointer border hover:border-blue-500 transition">
                                <input type="radio" name="role" value="agent" {{ old('role', $user->role) == 'agent' ? 'checked' : '' }} class="text-blue-600">
                                <span class="ml-3 text-sm font-bold text-slate-700">Simple Agent</span>
                            </label>
                            <label class="flex items-center p-4 bg-white rounded-xl cursor-pointer border hover:border-blue-500 transition">
                                <input type="radio" name="role" value="chef_service" {{ old('role', $user->role) == 'chef_service' ? 'checked' : '' }} class="text-blue-600">
                                <span class="ml-3 text-sm font-bold text-slate-700">Chef de Service</span>
                            </label>
                            <label class="flex items-center p-4 bg-white rounded-xl cursor-pointer border hover:border-blue-500 transition">
                                <input type="radio" name="role" value="admin_direction" {{ old('role', $user->role) == 'admin_direction' ? 'checked' : '' }} class="text-blue-600">
                                <span class="ml-3 text-sm font-bold text-slate-700">Admin Direction</span>
                            </label>
                            <label class="flex items-center p-4 bg-white rounded-xl cursor-pointer border hover:border-blue-500 transition">
                                <input type="radio" name="role" value="admin_rh" {{ old('role', $user->role) == 'admin_rh' ? 'checked' : '' }} class="text-blue-600">
                                <span class="ml-3 text-sm font-bold text-slate-700">Admin RH (Global)</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <div class="flex gap-4 pt-4">
                        <a href="{{ route('users.pending') }}" class="w-1/3 py-4 text-center bg-gray-100 text-gray-500 rounded-2xl font-black uppercase tracking-widest text-[10px]">Annuler</a>
                        <button type="submit" class="w-2/3 py-4 bg-indigo-600 hover:bg-blue-700 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl transition-all active:scale-95">
                            Confirmer & Activer le compte
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
