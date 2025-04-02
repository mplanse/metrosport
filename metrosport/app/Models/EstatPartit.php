<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstatPartit extends Model
{
    use HasFactory;

    protected $table = 'estat_partit';
    protected $primaryKey = 'id_estat';
    public $timestamps = false;

    protected $fillable = [
        'nom_estat'
    ];

    /**
     * Relación con partits
     */
    public function partits()
    {
        return $this->hasMany(Partit::class, 'estat_partit_id_estat', 'id_estat');
    }
}
