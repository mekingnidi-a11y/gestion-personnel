<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Bureau extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'bureaux';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id', 'code', 'service_id', 'code_service', 'nom', 'localisation', 'capacite', 'code_central', 'est_synchronise'];

    protected static function boot()
    {
        parent::boot();

        // On utilise 'saving' qui couvre à la fois la création et la mise à jour
        static::saving(function ($model) {
            // On régénère le code si c'est un nouveau bureau OU si le nom a changé
            if ($model->isDirty('nom') || !$model->exists) {
                $model->generateBureauCode();
            }
        });
    }

    /**
     * Logique de génération du code métier
     */
    public function generateBureauCode()
    {
        $service = Service::find($this->service_id);
        if (!$service) return;

        // Nettoyage du nom pour l'acronyme
        $nomNettoye = str_replace(["'", "-"], " ", strtoupper($this->nom));
        $words = explode(' ', $nomNettoye);
        $acronym = "";
        $ignore = ['DES', 'DE', 'LA', 'LES', 'DU', 'ET', 'LE', 'AUX', 'D', 'L', 'AU'];

        foreach ($words as $w) {
            if (!in_array($w, $ignore) && strlen($w) > 0) {
                $acronym .= substr($w, 0, 1);
            }
        }

        // Si l'acronyme est vide (ex: nom trop court), on prend les 3 premières lettres
        if(empty($acronym)) $acronym = substr(str_replace(' ', '', $nomNettoye), 0, 3);

        // Construction du nouveau code : CodeService-B-Acronyme
        $newCode = $service->code . '-B' . $acronym;

        // Vérification des doublons pour éviter les collisions
        $count = self::where('code', 'like', $newCode . '%')
                     ->where('id', '!=', $this->id)
                     ->count();
        
        $this->code = ($count > 0) ? $newCode . ($count + 1) : $newCode;
        
        // On s'assure que le code_service texte est aussi à jour
        $this->code_service = $service->code;
    }

    public function service() { 
        return $this->belongsTo(Service::class, 'service_id'); 
    }
}
