<?php

namespace App\Http\Controllers;

use App\Models\Lliga;
use Illuminate\Http\Request;

class LligaController extends Controller
{
    public function index()
    {
        return view('lligues.index');
    }

    public function getLligues()
    {
        return response()->json(Lliga::all());
    }

public function getLligaInfo($id)
{
    $lliga = Lliga::with(['equips', 'partits.estat', 'partits.ubicacio'])->find($id);

    if (!$lliga) {
        return response()->json(['error' => 'Liga no encontrada'], 404);
    }

    return response()->json($lliga);
}
}
