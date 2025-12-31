<x-app-layout>
    @section('header-title', 'Messagerie Interne')
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- COLONNE GAUCHE : LISTE DES DISCUSSIONS -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">
                    <div class="bg-indigo-900 p-6 flex justify-between items-center text-white">
                        <h2 class="text-sm font-black uppercase tracking-widest">Discussions récentes</h2>
                    </div>
                    <div class="divide-y divide-gray-100 max-h-[650px] overflow-y-auto custom-scrollbar">
                        @forelse($messages as $msg)
                            @php 
                                $interlocuteur = ($msg->sender_id == auth()->id()) ? $msg->receiver : $msg->sender;
                                $isUnread = !$msg->read_at && $msg->receiver_id === auth()->id();
                            @endphp
                            <a href="{{ route('messages.show', $interlocuteur->id) }}" class="block p-6 hover:bg-indigo-50 transition relative">
                                @if($isUnread)<div class="absolute left-0 top-0 bottom-0 w-1.5 bg-indigo-600"></div>@endif
                                <div class="flex justify-between items-center mb-1">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center text-[10px] font-black text-indigo-600 uppercase">
                                            {{ substr($interlocuteur->username, 0, 2) }}
                                        </div>
                                        <span class="text-[10px] font-black uppercase text-indigo-900">{{ $interlocuteur->username }}</span>
                                    </div>
                                    <span class="text-[9px] text-gray-400 font-bold uppercase">{{ $msg->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs {{ $isUnread ? 'font-bold text-gray-900' : 'text-gray-500' }} line-clamp-1 ml-8">{{ $msg->contenu }}</p>
                            </a>
                        @empty
                            <div class="p-20 text-center text-gray-400 uppercase text-xs font-black">Aucune discussion</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- COLONNE DROITE : NOUVELLE CONVERSATION -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow-2xl rounded-3xl p-8 border border-gray-100 sticky top-8">
                    <h3 class="text-xs font-black uppercase text-indigo-900 mb-6 tracking-widest border-b border-indigo-50 pb-4">Nouvelle Discussion</h3>
                    
                    <form action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                        <!-- DESTINATAIRE -->
                        <div>
                            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Destinaire</label>
                            <select name="receiver_id" required class="w-full px-4 py-3 bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-indigo-500/5 rounded-2xl text-xs font-bold text-gray-700 transition-all">
                                <option value="">-- Sélectionner un agent --</option>
                                @foreach(\App\Models\User::where('id', '!=', auth()->id())->where('est_valide', true)->get() as $dest)
                                    <option value="{{ $dest->id }}">{{ strtoupper($dest->username) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- MESSAGE -->
                        <div>
                            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Votre Message</label>
                            <textarea name="contenu" rows="4" required class="w-full px-4 py-3 bg-gray-50 border-transparent focus:bg-white focus:ring-4 focus:ring-indigo-500/5 rounded-2xl text-xs font-bold text-gray-700 transition-all" placeholder="Écrivez ici..."></textarea>
                        </div>

                        <!-- PIÈCE JOINTE -->
                        <div>
                            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Document (Optionnel)</label>
                            <input type="file" name="piece_jointe" class="text-[9px] text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>

                        <!-- BOUTON ENVOYER -->
                        <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl shadow-indigo-100 transition-all active:scale-95 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            Envoyer le message
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
