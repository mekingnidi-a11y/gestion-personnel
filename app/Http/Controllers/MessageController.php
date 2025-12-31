<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller {
    
    public function index() {
        $user = Auth::user();
        
        $messages = Message::where('receiver_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique(function ($item) use ($user) {
                return $item->sender_id == $user->id ? $item->receiver_id : $item->sender_id;
            });

        $users = User::where('id', '!=', $user->id)->orderBy('username')->get();
        return view('messages.index', compact('messages', 'users'));
    }

    public function show($interlocuteurId) {
        $user = Auth::user();
        $interlocuteur = User::findOrFail($interlocuteurId);

        $conversation = Message::where(function($q) use ($user, $interlocuteurId) {
                $q->where('sender_id', $user->id)->where('receiver_id', $interlocuteurId);
            })->orWhere(function($q) use ($user, $interlocuteurId) {
                $q->where('sender_id', $interlocuteurId)->where('receiver_id', $user->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        Message::where('receiver_id', $user->id)
            ->where('sender_id', $interlocuteurId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.show', compact('conversation', 'interlocuteur'));
    }

    public function download(Message $message) {
        if (Auth::id() !== $message->receiver_id) abort(403);
        
        if (!$message->piece_jointe || !Storage::disk('public')->exists($message->piece_jointe)) {
            return back()->with('error', 'Fichier introuvable.');
        }

        $fullPath = storage_path('app/public/' . $message->piece_jointe);
        $fileName = basename($fullPath);

        $message->update(['piece_jointe' => null]);

        return response()->download($fullPath, $fileName)->deleteFileAfterSend(true);
    }

    public function store(Request $request) {
        $request->validate([
            'contenu' => 'required',
            'receiver_id' => 'required',
            'piece_jointe' => 'nullable|file|max:5120',
        ]);

        $path = $request->hasFile('piece_jointe') 
            ? $request->file('piece_jointe')->store('messagerie', 'public') 
            : null;

         Message::create([
        'sender_id' => Auth::id(),
        'receiver_id' => $request->receiver_id,
        'objet' => $request->objet ?? 'Réponse',
        'contenu' => $request->contenu,
        'piece_jointe' => $path,
        'est_diffusion' => $request->has('est_diffusion'),
    ]);


        // On retourne avec un succès vide ou un mot-clé technique
        // car le texte "Message envoyé" est déjà écrit en dur dans votre vue show
        return back();
    }
}
