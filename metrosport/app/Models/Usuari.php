<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Cambiar Model por Authenticatable
use Illuminate\Notifications\Notifiable;

class Usuari extends Authenticatable
{
    use HasFactory, Notifiable; // Agregar Notifiable para manejar notificaciones si lo deseas

    protected $table = 'usuari';
    protected $primaryKey = 'id_usuari';
    public $timestamps = false;
    protected $fillable = ['nom_usuari', 'mail', 'contrasenya'];
    protected $hidden = ['contrasenya']; // Oculta la contraseña en respuestas JSON

    public function equips()
    {
        return $this->hasMany(Equip::class, 'usuari_id_usuari');
    }
}
