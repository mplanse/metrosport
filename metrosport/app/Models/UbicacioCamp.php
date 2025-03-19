<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UbicacioCamp extends Model
{
    use HasFactory;

    protected $table = 'ubicacio_camp';
    protected $primaryKey = 'id_ubicacio_camp';
    public $timestamps = false;

    public function equip()
    {
        return $this->belongsTo(Equip::class, 'equip_usuari_id_usuari');
    }
}
