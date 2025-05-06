<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partit extends Model
{
    use HasFactory;

    protected $table = 'partit';
    protected $primaryKey = 'id_partit';
    public $timestamps = false;

    protected $fillable = [
        'lliga_id_lliga',
        'ubicacio_camp_id_ubicacio_camp',
        'estat_partit_id_estat',
        'dia_hora_id',
        'jornada'
    ];

    /**
     * Relación con la liga
     */
    public function lliga()
    {
        return $this->belongsTo(Lliga::class, 'lliga_id_lliga', 'id_lliga');
    }

    /**
     * Relación con la ubicación del campo
     */
    public function ubicacio()
    {
        return $this->belongsTo(UbicacioCamp::class, 'ubicacio_camp_id_ubicacio_camp', 'id_ubicacio_camp');
    }

    /**
     * Relación con el estado del partido
     */
    public function estat()
    {
        return $this->belongsTo(EstatPartit::class, 'estat_partit_id_estat', 'id_estat');
    }

    /**
     * Relación con dia y hora
     */
    public function diaHora()
    {
        return $this->belongsTo(DiaHora::class, 'dia_hora_id', 'id');
    }

    /**
     * Relación con equipos (muchos a muchos)
     */
    public function equips()
    {
        return $this->belongsToMany(
            Equip::class,
            'partit_has_equip',
            'partit_id_partit',
            'equip_usuari_id_usuari'
        )
        ->withPivot('partit_lliga_id_lliga', 'gols', 'local_visitant'); // Incluye todas las columnas necesarias de la tabla intermedia
    }
}
