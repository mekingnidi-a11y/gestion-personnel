<x-app-layout>
    <x-slot name="header-title">Configuration de sécurité</x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-2xl rounded-[2.5rem] border border-indigo-50">
                <div class="mb-8 text-center">
                    <h2 class="text-2xl font-black text-indigo-950 uppercase italic">
                        {{ is_null(Auth::user()->password) ? 'Créer votre mot de passe' : 'Nouveau mot de passe' }}
                    </h2>
                    <p class="text-xs text-gray-500 mt-2 font-bold uppercase tracking-widest">
                        {{ is_null(Auth::user()->password) ? 'Votre accès a été validé. Choisissez un mot de passe pour commencer.' : 'Votre mot de passe a été réinitialisé par l\'admin.' }}
                    </p>
                </div>

                <form method="post" action="{{ route('profile.force-password.update') }}" class="space-y-6">
                    @csrf

                    {{-- On n'affiche ce champ QUE si l'utilisateur a déjà un mot de passe --}}
                    @if(!is_null(Auth::user()->password))
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Mot de passe actuel</label>
                        <x-text-input name="current_password" type="password" class="mt-1 block w-full rounded-2xl bg-gray-50 border-transparent focus:bg-white" />
                        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                    </div>
                    @endif

                    <div>
                        <label class="block text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-1">Nouveau mot de passe personnel</label>
                        <x-text-input name="password" type="password" class="mt-1 block w-full rounded-2xl bg-gray-50 border-transparent focus:bg-white" required />
                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Confirmer le mot de passe</label>
                        <x-text-input name="password_confirmation" type="password" class="mt-1 block w-full rounded-2xl bg-gray-50 border-transparent focus:bg-white" required />
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black uppercase text-xs tracking-[0.2em] shadow-xl hover:bg-indigo-700 transition active:scale-95">
                            Activer mon accès au portail
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
