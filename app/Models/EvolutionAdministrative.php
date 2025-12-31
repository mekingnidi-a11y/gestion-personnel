<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EvolutionAdministrative extends Model
{
    // FORCE LE NOM DE LA TABLE POUR ÉVITER L'ERREUR 1146
    protected $table = 'evolutions_administratives';

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }
}
