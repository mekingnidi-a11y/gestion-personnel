<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Authenticatable
{
    use HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'direction_id', // Ce champ stockera l'UUID aléatoire de la direction
        'est_valide',
        'doit_changer_password',
        'a_demande_reset',
    ];

    public function direction()
    {
        // On lie le direction_id de l'utilisateur au direction_id de la table directions
        return $this->belongsTo(Direction::class, 'direction_id', 'id');
    }
}
