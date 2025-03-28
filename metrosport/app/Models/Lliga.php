<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lliga extends Model
{
    use HasFactory;

    protected $table = 'lliga';
    protected $primaryKey = 'id_lliga';
    public $timestamps = false;

    protected $fillable = [
        'nom_lliga',
        'lloc_lliga',
        'nro_equips_participants',
        'preu_entrada',
        'url_imagen',
        'data_inici',
        'data_fi',
        'participants_actualment',
        'esport',
        'persones_equip',
    ];

    public function equips()
    {
        return $this->hasMany(Equip::class, 'lliga_id_lliga');
    }

    public function partits()
    {
        return $this->hasMany(Partit::class, 'lliga_id_lliga');
    }
}
