<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Message extends Model {
    use HasUuids;

    // 'piece_jointe' a été ajouté ici pour autoriser l'enregistrement en base
    protected $fillable = [
        'sender_id', 
        'receiver_id', 
        'objet', 
        'contenu', 
        'piece_jointe', 
        'est_diffusion', 
        'read_at'
    ];

    public function sender() { return $this->belongsTo(User::class, 'sender_id'); }
    public function receiver() { return $this->belongsTo(User::class, 'receiver_id'); }

// app/Models/Message.php

public function readers() { 
    // On définit explicitement : table pivot, clé étrangère message, clé étrangère user
    return $this->belongsToMany(User::class, 'message_user_read', 'message_id', 'user_id')
                ->withPivot('read_at'); 
}


}
