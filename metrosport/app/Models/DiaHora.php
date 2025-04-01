<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaHora extends Model
{
    use HasFactory;

    protected $table = 'dia_hora';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'dia',
        'hora'
    ];

    // Relación con partidos
    public function partits()
    {
        return $this->hasMany(Partit::class, 'dia_hora_id', 'id');
    }

    // Relación con equipos (disponibilidad)
    public function equips()
    {
        return $this->belongsToMany(Equip::class, 'equip_has_dia_hora', 'dia_hora_id', 'equip_usuari_id_usuari');
    }
}
