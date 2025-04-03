<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DiaHoraController extends Controller
{
    public function store(Request $request)
    {
        $usuari_id = Auth::id();


        try {
            // Borrar disponibilidad anterior
            DB::table('equip_has_dia_hora')->where('equip_usuari_id_usuari', $usuari_id)->delete();

            // Insertar nuevos valores seleccionados
            foreach ($request->all() as $key => $value) {
                if (str_starts_with($key, 'checkbox_')) {
                    DB::table('equip_has_dia_hora')->insert([
                        'equip_usuari_id_usuari' => $usuari_id,
                        'dia_hora_id' => $value,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Horari actualitzat correctament.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Error al actualitzar l'horari. Intenta-ho de nou.");
        }
    }

}
