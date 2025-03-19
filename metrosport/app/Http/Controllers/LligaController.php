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
}
