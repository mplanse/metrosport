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

    public function lliga()
    {
        return $this->belongsTo(Lliga::class, 'lliga_id_lliga');
    }

    public function ubicacioCamp()
    {
        return $this->belongsTo(UbicacioCamp::class, 'ubicacio_camp_id_ubicacio_camp');
    }

    public function equips()
    {
        return $this->belongsToMany(Equip::class, 'partit_has_equip', 'partit_id_partit', 'equip_usuari_id_usuari')->withPivot('gols');
    }
}
