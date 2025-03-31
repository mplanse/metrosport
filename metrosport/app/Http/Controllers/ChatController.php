<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Missatge;

class ChatController extends Controller
{
    public function index()
    {
        $chat = Missatge::all();
        return view('chat.index', compact('chat'));
    }
}
