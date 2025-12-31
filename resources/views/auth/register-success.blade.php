<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-50 p-6">
        <div class="max-w-md w-full bg-white p-10 rounded-[3rem] shadow-2xl border border-gray-100 text-center">
            <!-- Icône Animée -->
            <div class="mb-6 flex justify-center">
                <div class="bg-green-100 p-4 rounded-full">
                    <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <h2 class="text-3xl font-black text-slate-900 uppercase tracking-tight mb-4">Demande Transmise !</h2>
            
            <p class="text-slate-600 leading-relaxed mb-8">
                Votre demande de création de compte pour le <span class="font-bold text-blue-600">Portail RH METP 2025</span> a bien été enregistrée.
            </p>

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8 text-left text-sm text-blue-700">
                <p class="font-bold uppercase mb-1">Prochaine étape :</p>
                Un administrateur de votre direction doit valider votre accès avant que vous ne puissiez vous connecter.
            </div>

            <a href="{{ route('login') }}" class="block w-full py-4 bg-slate-900 hover:bg-blue-600 text-white rounded-2xl font-black uppercase tracking-widest transition-all">
                Retour à l'accueil
            </a>
            
            <p class="mt-6 text-xs text-slate-400 font-bold uppercase tracking-widest">
                Ministère de l'Équipement et des Travaux Publics
            </p>
        </div>
    </div>
</x-guest-layout>
