<x-app-layout>
    <x-slot name="header-title">Validation de l'Agent</x-slot>

    <div class="py-12 transition-colors duration-300">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-2xl rounded-[2rem] p-10 border border-gray-100 dark:border-gray-700 transition-colors duration-300">
                <h2 class="text-2xl font-black text-slate-800 dark:text-white uppercase mb-6 tracking-tight">Configurer & Valider l'accès</h2>
                
                <form method="POST" action="{{ route('users.confirm-validation', $user->id) }}">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Username -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-400 dark:text-gray-500 uppercase mb-2 ml-1 tracking-widest">Identifiant (Username)</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" 
                                class="w-full px-5 py-3 bg-gray-50 dark:bg-gray-900/50 border-transparent dark:border-gray-700 rounded-2xl focus:ring-4 focus:ring-blue-500/5 dark:focus:ring-indigo-600/20 font-bold text-slate-700 dark:text-gray-200 transition-all">
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-400 dark:text-gray-500 uppercase mb-2 ml-1 tracking-widest">Email Professionnel</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                class="w-full px-5 py-3 bg-gray-50 dark:bg-gray-900/50 border-transparent dark:border-gray-700 rounded-2xl focus:ring-4 focus:ring-blue-500/5 dark:focus:ring-indigo-600/20 font-bold text-slate-700 dark:text-gray-200 transition-all">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Direction -->
                    <div class="mb-8 space-y-2">
                        <label class="block text-xs font-black text-slate-400 dark:text-gray-500 uppercase mb-2 ml-1 tracking-widest">Direction d'affectation</label>
                        <select name="direction_id" class="w-full px-5 py-3 bg-gray-50 dark:bg-gray-900/50 border-transparent dark:border-gray-700 rounded-2xl font-bold text-slate-700 dark:text-gray-200 shadow-inner focus:ring-4 focus:ring-indigo-600/20 transition-all">
                            @foreach($directions as $direction)
                                <option value="{{ $direction->id }}" {{ (old('direction_id', $user->direction_id) == $direction->id) ? 'selected' : '' }} class="dark:bg-gray-800">
                                    {{ $direction->nom }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('direction_id')" class="mt-2" />
                    </div>

                    <!-- ROLE -->
                    <div class="bg-blue-50 dark:bg-indigo-900/20 p-6 rounded-[1.5rem] border border-blue-100 dark:border-indigo-900/30 mb-8 transition-colors">
                        <label class="block text-xs font-black text-blue-600 dark:text-indigo-400 uppercase mb-4 tracking-widest">Attribuer un Rôle Système</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @php
                                $roles = [
                                    'agent' => 'Simple Agent',
                                    'chef_service' => 'Chef de Service',
                                    'admin_direction' => 'Admin Direction',
                                    'admin_rh' => 'Admin RH (Global)'
                                ];
                            @endphp

                            @foreach($roles as $value => $label)
                                <label class="flex items-center p-4 bg-white dark:bg-gray-800 rounded-xl cursor-pointer border border-transparent dark:border-gray-700 hover:border-blue-500 dark:hover:border-indigo-500 transition-all shadow-sm">
                                    <input type="radio" name="role" value="{{ $value }}" {{ old('role', $user->role) == $value ? 'checked' : '' }} 
                                        class="text-blue-600 dark:text-indigo-500 focus:ring-blue-500 dark:bg-gray-900 dark:border-gray-600">
                                    <span class="ml-3 text-sm font-bold text-slate-700 dark:text-gray-300">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <div class="flex gap-4 pt-4">
                        <a href="{{ route('users.pending') }}" 
                            class="w-1/3 py-4 text-center bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            Annuler
                        </a>
                        <button type="submit" 
                            class="w-2/3 py-4 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl shadow-indigo-100 dark:shadow-none transition-all active:scale-95">
                            Confirmer & Activer le compte
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
