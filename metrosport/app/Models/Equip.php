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
}
