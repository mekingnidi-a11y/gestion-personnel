<x-app-layout>
    @section('header-title', 'Discussion avec ' . $interlocuteur->username)

    <style>
        /* 1. CONFIGURATION DE LA HAUTEUR DU CADRE DE DISCUSSION */
        .scroll-container {
            height: 180px !important; 
            overflow-y: scroll !important;
            display: block !important;
            scrollbar-width: thin !important;
            scrollbar-color: #4f46e5 #f1f1f1 !important;
        }

        /* 2. BLOQUER LE SCROLL DE LA PAGE POUR FIXER LE CADRE */
        html, body {
            overflow: hidden !important;
            height: 100vh !important;
        }

        /* 3. STYLE DE LA BARRE DE DEFILEMENT ET FLECHES */
        .scroll-container::-webkit-scrollbar { width: 14px !important; display: block !important; }
        .scroll-container::-webkit-scrollbar-track { background: #f1f1f1 !important; }
        .scroll-container::-webkit-scrollbar-thumb {
            background-color: #4f46e5 !important;
            border-radius: 10px !important;
            border: 2px solid #f1f1f1 !important;
        }
        .scroll-container::-webkit-scrollbar-button:single-button {
            display: block !important;
            background-color: #4f46e5 !important;
            height: 14px !important;
        }
        .scroll-container::-webkit-scrollbar-button:single-button:vertical:decrement {
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='www.w3.org' width='100' height='100' fill='white'><polygon points='50,0 0,100 100,100'/></svg>") !important;
            background-size: 8px 8px !important; background-repeat: no-repeat !important; background-position: center !important;
        }
        .scroll-container::-webkit-scrollbar-button:single-button:vertical:increment {
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='www.w3.org' width='100' height='100' fill='white'><polygon points='0,0 100,0 50,100'/></svg>") !important;
            background-size: 8px 8px !important; background-repeat: no-repeat !important; background-position: center !important;
        }
    </style>

    <div class="py-6 max-w-6xl mx-auto px-4 h-full flex items-center justify-center">
        <!-- CADRE PRINCIPAL -->
        <div class="bg-white shadow-2xl rounded-[2.5rem] overflow-hidden border border-gray-200 flex flex-col w-full">
            
            <!-- HEADER (HAUTEUR FIXE SANS NOTIFICATION) -->
            <div class="bg-indigo-900 p-5 text-white flex items-center shrink-0 h-20">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('messages.index') }}" class="text-indigo-300 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <h1 class="text-sm font-black uppercase tracking-widest">
                        Conversation : {{ $interlocuteur->username }}
                    </h1>
                </div>
            </div>

            <!-- ZONE DE DISCUSSION (SEULE ZONE QUI DEFILE) -->
            <div class="scroll-container p-8 space-y-6 bg-slate-50">
                @foreach($conversation as $msg)
                    @php $isMe = ($msg->sender_id == auth()->id()); @endphp
                    <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} mb-4">
                        <div class="max-w-[75%] p-4 rounded-[1.5rem] shadow-sm {{ $isMe ? 'bg-indigo-600 text-white' : 'bg-white text-gray-800 border border-gray-100' }}">
                            <p class="text-sm leading-relaxed">{!! nl2br(e($msg->contenu)) !!}</p>
                            @if($msg->piece_jointe)
                                <div class="mt-3 pt-2 border-t {{ $isMe ? 'border-indigo-400' : 'border-gray-100' }}">
                                    @if(!$isMe)
                                        <a href="{{ route('messages.download', $msg->id) }}" class="flex items-center text-[10px] font-black uppercase text-amber-500 hover:text-amber-600 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            Télécharger le document
                                        </a>
                                    @else
                                        <span class="text-[8px] font-bold opacity-60 italic">📎 Pièce jointe transmise</span>
                                    @endif
                                </div>
                            @endif
                            <span class="block mt-1 text-[8px] opacity-50 text-right font-bold tracking-tighter">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- ZONE DE RÉPONSE -->
            <div class="p-6 bg-white border-t border-gray-100 shrink-0">
                <form action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data" x-data="{ fileName: '' }">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $interlocuteur->id }}">
                    <div class="flex items-end space-x-4">
                        <textarea name="contenu" rows="2" required class="flex-1 border-gray-200 rounded-2xl text-sm p-4 bg-gray-50 focus:ring-indigo-600 border-none shadow-inner" placeholder="Répondre..."></textarea>
                        <div class="flex flex-col space-y-3">
                            <label class="cursor-pointer text-indigo-600 p-1 hover:bg-indigo-50 rounded-full transition">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                <input type="file" name="piece_jointe" class="hidden" @change="fileName = $event.target.files[0].name">
                            </label>
                            <button type="submit" class="bg-indigo-900 text-white p-3 rounded-2xl shadow-xl hover:bg-black transition-all transform active:scale-95">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                        </div>
                    </div>
                    <p x-show="fileName" class="text-[9px] text-indigo-600 mt-2 font-bold italic px-2" x-text="'Fichier : ' + fileName"></p>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatZone = document.querySelector('.scroll-container');
            if(chatZone) chatZone.scrollTop = chatZone.scrollHeight;
        });
    </script>
</x-app-layout>
