<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partit extends Model
{
    use HasFactory;

    protected $table = 'partit';
    protected $primaryKey = 'id_partit';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'lliga_id_lliga',
        'ubicacio_camp_id_ubicacio_camp',
        'estat_partit_id_estat',
        'dia_hora_id',
        'jornada'     // Si quieres guardar también la jornada
    ];

    // Relación con Lliga
    public function lliga()
    {
        return $this->belongsTo(Lliga::class, 'lliga_id_lliga', 'id_lliga');
    }

    // Relación con Ubicació del campo
    public function ubicacio()
    {
        return $this->belongsTo(UbicacioCamp::class, 'ubicacio_camp_id_ubicacio_camp', 'id_ubicacio_camp');
    }

    // Relación con Estado del partido
    public function estat()
    {
        return $this->belongsTo(EstatPartit::class, 'estat_partit_id_estat', 'id_estat');
    }

    // Nueva relación con DiaHora
    public function diaHora()
    {
        return $this->belongsTo(DiaHora::class, 'dia_hora_id', 'id');
    }

    // Relación con Equipos (Muchos a Muchos)
    public function equips()
    {
        return $this->belongsToMany(
            Equip::class,
            'partit_has_equip',
            'partit_id_partit',
            'equip_usuari_id_usuari'
        )->withPivot('gols', 'local_visitant');
    }
}
