<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EditarPerfilController extends Controller
{
    public function showEditarPerfil()
{
    $usuari_id = auth()->user()->id_usuari;

    // Obtener las horas marcadas
    $horasMarcadas = DB::table('equip_has_dia_hora')
        ->where('equip_usuari_id_usuari', $usuari_id)
        ->pluck('dia_hora_id')
        ->toArray();

    // Obtener los datos del equipo (nombre, imagen)
    $equip = DB::table('equip')
        ->where('usuari_id_usuari', $usuari_id)
        ->first();

    return view('usuaris.editar-perfil', compact('horasMarcadas', 'equip'));
}
}
