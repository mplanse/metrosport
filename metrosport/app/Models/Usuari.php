<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuari extends Model
{
    use HasFactory;

    protected $table = 'usuari';
    protected $primaryKey = 'id_usuari';
    public $timestamps = false;

    public function equips()
    {
        return $this->hasMany(Equip::class, 'usuari_id_usuari');
    }
}
