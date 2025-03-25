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

    public function usuari()
    {
        return $this->belongsTo(Usuari::class, 'usuari_id_usuari');
    }

    public function lliga()
    {
        return $this->belongsTo(Lliga::class, 'lliga_id_lliga');
    }

    public function diaHoras()
    {
        return $this->belongsToMany(DiaHora::class, 'equip_has_dia_hora', 'equip_usuari_id_usuari', 'dia_hora_id');
    }
    // App\Models\Equip.php
    public function ubicacioCamp()
{
    return $this->hasOne(UbicacioCamp::class, 'equip_usuari_id_usuari', 'usuari_id_usuari');
}



}
