<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartitHasEquip extends Model
{
    use HasFactory;

    protected $table = 'partit_has_equip';
    protected $primaryKey = null; // Esta tabla tiene clave primaria compuesta
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'partit_id_partit',
        'partit_lliga_id_lliga',
        'equip_usuari_id_usuari',
        'gols',
        'local_visitant'
    ];

    // Relación con Partit
    public function partit()
    {
        return $this->belongsTo(Partit::class, 'partit_id_partit', 'id_partit');
    }

    // Relación con Equip
    public function equip()
    {
        return $this->belongsTo(Equip::class, 'equip_usuari_id_usuari', 'usuari_id_usuari');
    }

    // Relación con Lliga
    public function lliga()
    {
        return $this->belongsTo(Lliga::class, 'partit_lliga_id_lliga', 'id_lliga');
    }
}
