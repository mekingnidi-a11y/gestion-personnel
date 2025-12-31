<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer une nouvelle Direction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('directions.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Nom de la Direction</label>
                        <input type="text" name="nom" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Type de Direction</label>
                        <select name="type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" required>
                            <option value="generale">Direction Générale</option>
                            <option value="centrale">Direction Centrale</option>
                            <option value="departementale">Direction Départementale</option>
                            <option value="rattache_cabinet">Rattachée au Cabinet</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Direction Parente (Si applicable)</label>
                        <select name="code_direction_parent" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                            <option value="">-- Aucune --</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->code }}">{{ $parent->nom }} ({{ $parent->type }})</option>
                            @endforeach
                        </select>
                    </div>
<!-- Insérer dans le formulaire existant après le type -->
<div class="grid grid-cols-2 gap-4">
    <div class="mb-4">
        <label class="block font-bold">Email de contact</label>
        <input type="email" name="contact_email" class="w-full border-gray-300 rounded shadow-sm">
    </div>
    <div class="mb-4">
        <label class="block font-bold">Téléphone</label>
        <input type="text" name="contact_telephone" class="w-full border-gray-300 rounded shadow-sm">
    </div>
</div>

<div class="mb-4">
    <label class="block font-bold">Référence Arrêté de création</label>
    <input type="text" name="arret_creation" class="w-full border-gray-300 rounded shadow-sm" placeholder="Ex: Arrêté n°2024-X du...">
</div>

<div class="mb-4">
    <label class="block font-bold">Missions</label>
    <textarea name="missions" rows="4" class="w-full border-gray-300 rounded shadow-sm"></textarea>
</div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-700">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
