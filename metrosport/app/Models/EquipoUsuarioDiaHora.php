<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class EquipoUsuarioDiaHora extends Model
{
    use HasFactory;

    protected $table = 'equip_usuari_dia_hora'; // Nombre de la tabla real
    protected $fillable = ['equip_usuari_id_usuari', 'dia_hora_id']; // Columnas permitidas
}
