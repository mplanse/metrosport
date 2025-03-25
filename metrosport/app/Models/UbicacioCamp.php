<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UbicacioCamp extends Model
{
    use HasFactory;

    protected $table = 'ubicacio_camp'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id_ubicacio_camp'; // Clave primaria de la tabla

    public $incrementing = true; // Indica que la clave primaria es autoincremental
    protected $keyType = 'int';
    public $timestamps = false; // La tabla no tiene created_at ni updated_at

    // Columnas permitidas para asignación masiva
    protected $fillable = [
        'id_ubicacio_camp',
        'nom_ubucacio',
        'equip_usuari_id_usuari'
    ];

    // 📌 Relación con `Partit` (Una ubicación puede tener muchos partidos)
    public function partits()
    {
        return $this->hasMany(Partit::class, 'ubicacio_camp_id_ubicacio_camp', 'id_ubicacio_camp');
    }

    // 📌 Relación con `Equip` (Una ubicación pertenece a un equipo y una liga)
    public function equip()
    {
        return $this->belongsTo(Equip::class, ['equip_usuari_id_usuari', 'equip_lliga_id_lliga'], ['usuari_id_usuari', 'lliga_id_lliga']);
    }
}
