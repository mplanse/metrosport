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
        ->leftJoin('ubicacio_camp', 'equip.usuari_id_usuari', '=', 'ubicacio_camp.equip_usuari_id_usuari')
        ->select('equip.nom_equip', 'equip.url_imagen', 'ubicacio_camp.nom_ubicacio')
        ->where('equip.usuari_id_usuari', $usuari_id)
        ->first();



    return view('usuaris.editar-perfil', compact('horasMarcadas', 'equip'));
}

public function guardarUbicacio(Request $request)
{
    $usuari_id = auth()->user()->id_usuari;

    DB::table('ubicacio_camp')->updateOrInsert(
        ['equip_usuari_id_usuari' => $usuari_id],
        ['nom_ubicacio' => $request->nom_ubicacio]
    );

    return redirect()->back()->with('success', 'Ubicación guardada correctamente');
}

}
