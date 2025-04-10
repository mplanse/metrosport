<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notificacions;
use Illuminate\Support\Facades\Auth;

class NotificacionsController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id_usuari;

        $notificacions = Notificacions::where('equip_usuari_id_usuari', $userId)
            ->orderBy('timestamp', 'desc')
            ->get();

        return view('notificacions.index', compact('notificacions'));
    }
}
