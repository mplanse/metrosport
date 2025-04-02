<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacions extends Model
{
    use HasFactory;

    protected $table = 'notificacions';

    // La tabla usa 'equip_usuari_id_usuari' como clave primaria
    protected $primaryKey = 'id_notificacio';

    // Deshabilitar timestamps si no existen campos created_at y updated_at
    public $timestamps = false;

    protected $fillable = [
        'missatge_notificacio',
        'equip_usuari_id_usuari',
        'timestamp'
    ];

    // Relación: Notificacions pertenece a un Equip
    public function equip()
    {
        return $this->belongsTo(Equip::class, 'equip_usuari_id_usuari', 'usuari_id_usuari');
    }
}
