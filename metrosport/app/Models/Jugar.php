<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jugar extends Model
{
    use HasFactory;

    protected $table = 'jugar';
    public $timestamps = false;

    protected $fillable = ['equip_usuari_id_usuari', 'lliga_id_lliga'];
}
