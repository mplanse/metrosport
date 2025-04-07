<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuari extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuari';
    protected $primaryKey = 'id_usuari';
    public $timestamps = false;
    protected $fillable = ['nom_usuari', 'mail', 'contrasenya'];
    protected $hidden = ['contrasenya']; 

    public function equips()
    {
        return $this->hasMany(Equip::class, 'usuari_id_usuari');
    }
}
