<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Afficher la liste des messages
     */
    public function index()
    {
        $userId = Auth::id();

        // On récupère :
        // 1. Les messages privés envoyés à l'utilisateur connecté
        // 2. Les messages de diffusion (broadcast)
        // 3. Les messages que l'utilisateur a lui-même envoyés
        $messages = Message::where(function($query) use ($userId) {
            $query->where('receiver_id', $userId)
                  ->orWhere('type', 'broadcast')
                  ->orWhere('sender_id', $userId);
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'desc')
        ->get();

        // Liste des utilisateurs pour le menu déroulant (sauf soi-même)
        $users = User::where('id', '!=', $userId)->get();

        return view('messages.index', compact('messages', 'users'));
    }

    /**
     * Enregistrer un nouveau message
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|min:1',
            'receiver_id' => 'nullable|exists:users,id',
            'is_broadcast' => 'nullable|boolean'
        ]);

        $type = 'private';

        // Vérification : Seul le staff peut envoyer en broadcast
        // Note : Assurez-vous d'avoir une colonne 'role' dans votre table users
        if ($request->has('is_broadcast') && Auth::user()->role === 'staff') {
            $type = 'broadcast';
            $receiverId = null; // Pas de destinataire unique pour une diffusion
        } else {
            $receiverId = $request->receiver_id;
            
            // Empêcher l'envoi privé sans destinataire
            if (!$receiverId) {
                return back()->with('error', 'Veuillez sélectionner un destinataire.');
            }
        }

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId,
            'type' => $type,
            'content' => $request->content,
        ]);

        return redirect()->route('messages.index')->with('success', 'Message envoyé !');
    }
}
