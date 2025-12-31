<x-app-layout>
    <x-slot name="header_title">Détails du Bureau</x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <div class="bg-white rounded-[3rem] shadow-2xl p-10 border border-gray-100">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <span class="text-[10px] font-black bg-indigo-100 text-indigo-700 px-4 py-1 rounded-full uppercase">
                        Code Bureau : {{ $bureau->code }}
                    </span>
                    <h1 class="text-3xl font-black text-slate-800 uppercase mt-4">{{ $bureau->nom }}</h1>
                </div>
                <a href="{{ route('bureaux.index') }}" class="text-slate-400 hover:text-slate-600 font-bold text-[10px] uppercase">Retour au répertoire</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <div class="p-6 bg-slate-50 rounded-3xl">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase mb-2">Service de rattachement</h3>
                    <p class="font-bold text-slate-700 uppercase text-sm">{{ $bureau->service->nom ?? 'N/A' }}</p>
                </div>
                <div class="p-6 bg-slate-50 rounded-3xl">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase mb-2">Localisation physique</h3>
                    <p class="font-bold text-slate-700 text-sm">{{ $bureau->localisation ?? 'Non spécifiée' }}</p>
                </div>
            </div>

            <div class="p-6 border border-gray-100 rounded-3xl">
                <h3 class="text-[10px] font-black text-slate-400 uppercase mb-2">Capacité d'accueil</h3>
                <p class="text-slate-600 font-bold">{{ $bureau->capacite ?? 0 }} poste(s) de travail</p>
            </div>
        </div>
    </div>
</x-app-layout>
