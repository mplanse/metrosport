<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Missatge;
use App\Models\Equip;
use Illuminate\Support\Facades\Auth; // Asegúrate de importar Auth
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat.index');
    }

    public function getMissatges()
    {
        $userId = Auth::id();

        $equip = Equip::where('usuari_id_usuari', $userId)->first();

        if (!$equip) {
            return response()->json(['error' => 'No se encontró un equipo para el usuario'], 404);
        }

        $lligaId = $equip->lliga_id_lliga;

        $missatges = Missatge::with(['origen'])
            ->where('lliga_id_lliga', $lligaId)
            ->orderBy('timestamp', 'asc')
            ->get();

        return response()->json([
            'missatges' => $missatges,
            'userId' => $userId,
        ]);
    }

    public function storeMissatge(Request $request)
    {
        $userId = Auth::id();

        // Validar los datos recibidos
        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Obtener el equipo del usuario autenticado
        $equip = Equip::where('usuari_id_usuari', $userId)->first();

        if (!$equip) {
            return response()->json(['error' => 'No se encontró un equipo para el usuario'], 404);
        }

        // Crear el nuevo mensaje
        $missatge = new Missatge();
        $missatge->timestamp = now(); // Fecha y hora actual
        $missatge->equip_usuari_id_usuari_origen = $userId; // ID del usuario autenticado
        $missatge->lliga_id_lliga = $equip->lliga_id_lliga; // Liga del equipo
        $missatge->text = $request->input('text'); // Texto del mensaje

        // Guardar el mensaje en la base de datos
        $missatge->save();

        return response()->json(['success' => 'Mensaje enviado correctamente', 'missatge' => $missatge], 201);
    }
}
