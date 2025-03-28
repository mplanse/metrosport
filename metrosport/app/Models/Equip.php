<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equip extends Model
{
    use HasFactory;

    protected $table = 'equip';
    protected $primaryKey = 'usuari_id_usuari';
    public $timestamps = false;

    protected $fillable = [
        'nom_equip',
        'usuari_id_usuari',
        'url_imagen',
        'lliga_id_lliga',
        'puntuacio_lliga',
        'puntuacio_equip',
    ];

    // Relación: Perteneix a una lliga
    public function lliga()
    {
        return $this->belongsTo(Lliga::class, 'lliga_id_lliga');
    }

    // Relación: Equip té una ubicación (relación 1:1)
    public function ubicacioCamp()
    {
        return $this->hasOne(UbicacioCamp::class, 'equip_usuari_id_usuari');
    }

    // Relación: Disponibilitat horària (muchos a muchos via equip_has_dia_hora)
    public function diaHoras()
    {
        return $this->belongsToMany(DiaHora::class, 'equip_has_dia_hora', 'equip_usuari_id_usuari', 'dia_hora_id');
    }

    // Relación: Partits jugats (a través de partit_has_equip, inclou el camp 'gols')
    public function partitsJugats()
    {
        return $this->belongsToMany(Partit::class, 'partit_has_equip', 'equip_usuari_id_usuari', 'partit_id_partit')
                    ->withPivot('gols', 'partit_lliga_id_lliga');
    }
}
