<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Missatge;
use App\Models\Equip;
use App\Models\Lliga;
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

        $lliga = Lliga::find($equip->lliga_id_lliga);

        if (!$lliga) {
            return response()->json(['error' => 'No se encontró la liga asociada'], 404);
        }

        $missatges = Missatge::with(['origen'])
            ->where('lliga_id_lliga', $lliga->id_lliga)
            ->orderBy('timestamp', 'asc')
            ->get();

        return response()->json([
            'missatges' => $missatges,
            'userId' => $userId,
            'lliga' => [
                'id' => $lliga->id_lliga,
                'nom_lliga' => $lliga->nom_lliga,
            ],
        ]);
    }

    public function storeMissatge(Request $request)
    {
        $userId = Auth::id();

        $validator = Validator::make($request->all(), [
            'text' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $equip = Equip::where('usuari_id_usuari', $userId)->first();

        if (!$equip) {
            return response()->json(['error' => 'No se encontró un equipo para el usuario'], 404);
        }

        $missatge = new Missatge();
        $missatge->timestamp = now();
        $missatge->equip_usuari_id_usuari_origen = $userId;
        $missatge->lliga_id_lliga = $equip->lliga_id_lliga;
        $missatge->text = $request->input('text');

        $missatge->save();

        return response()->json(['success' => 'Mensaje enviado correctamente', 'missatge' => $missatge], 201);
    }
}
