<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Service extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id', 'code', 'direction_id', 'code_direction', 'nom', 'missions'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            $service->generateServiceCode();
        });

        static::updating(function ($service) {
            if ($service->isDirty('nom')) {
                $service->generateServiceCode();
            }
        });
    }

    public function generateServiceCode()
    {
        $direction = Direction::find($this->direction_id);
        if (!$direction) return;

        $nomNettoye = str_replace("'", " ", strtoupper($this->nom));
        $words = explode(' ', $nomNettoye);
        $acronym = "";
        $ignore = ['DES', 'DE', 'LA', 'LES', 'DU', 'ET', 'LE', 'AUX', 'D', 'L'];

        foreach ($words as $w) {
            if (!in_array($w, $ignore) && strlen($w) > 1) {
                $acronym .= substr($w, 0, 1);
            }
        }

        if(empty($acronym)) $acronym = substr(str_replace(' ', '', $nomNettoye), 0, 3);

        $newCode = $direction->code . '-' . $acronym;

        $count = self::where('code', 'like', $newCode . '%')->where('id', '!=', $this->id)->count();
        $this->code = ($count > 0) ? $newCode . ($count + 1) : $newCode;
        $this->code_direction = $direction->code;
    }

    public function direction() {
        return $this->belongsTo(Direction::class, 'direction_id');
    }

    /**
     * RELATION AVEC LES BUREAUX (Indispensable pour la suppression)
     */
    public function bureaux()
    {
        // On anticipe la liaison par service_id pour les bureaux
        return $this->hasMany(Bureau::class, 'code_service', 'code');
    }
}
