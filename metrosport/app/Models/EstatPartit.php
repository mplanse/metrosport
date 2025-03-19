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
}
