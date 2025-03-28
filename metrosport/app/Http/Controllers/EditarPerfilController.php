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

    return redirect()->back()->with('success', 'Ubicació guardada correctament');
}

public function updatePerfil(Request $request)
{
    $request->validate([
        'nom_equip' => 'required|string|max:255',
        'mail' => 'required|email|max:255',
        'url_imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $usuari_id = auth()->user()->id_usuari;

    // Actualizar el correo del usuario
    DB::table('usuari')->where('id_usuari', $usuari_id)->update([
        'mail' => $request->mail,
    ]);

    // Actualizar los datos del equipo
    $data = ['nom_equip' => $request->nom_equip];

    // Si se proporcionó una nueva imagen
    if ($request->hasFile('url_imagen')) {
        $imagen = $request->file('url_imagen');

        // Genera un nombre único para el archivo
        $nombreArchivo = time() . '_' . $usuari_id . '.' . $imagen->getClientOriginalExtension();

        // Directorio de destino (asegúrate de que exista)
        $directorio = public_path('assets/equipos');

        // Crear el directorio si no existe
        if (!file_exists($directorio)) {
            mkdir($directorio, 0755, true);
        }

        // Mover el archivo al directorio de destino
        $imagen->move($directorio, $nombreArchivo);

        // Guardar la ruta relativa en la base de datos
        $data['url_imagen'] = 'assets/equipos/' . $nombreArchivo;
    }

    DB::table('equip')->where('usuari_id_usuari', $usuari_id)->update($data);

    return redirect()->route('editar-perfil')->with('success', 'Perfil actualitzat correctament.');
}

}
