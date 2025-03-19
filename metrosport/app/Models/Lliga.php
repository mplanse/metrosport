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

    public function equips()
    {
        return $this->hasMany(Equip::class, 'lliga_id_lliga');
    }

    public function partits()
    {
        return $this->hasMany(Partit::class, 'lliga_id_lliga');
    }
}
