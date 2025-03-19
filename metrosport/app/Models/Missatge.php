<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Missatge extends Model
{
    use HasFactory;

    protected $table = 'missatge';
    protected $primaryKey = 'id_missatge';
    public $timestamps = false;

    public function origen()
    {
        return $this->belongsTo(Equip::class, 'equip_usuari_id_usuari_origen');
    }

    public function desti()
    {
        return $this->belongsTo(Equip::class, 'equip_usuari_id_usuari_desti');
    }

    public function lliga()
    {
        return $this->belongsTo(Lliga::class, 'lliga_id_lliga');
    }
}
