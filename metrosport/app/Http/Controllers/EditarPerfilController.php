<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EditarPerfilController extends Controller
{
    public function showEditarPerfil()
    {
        return view('usuaris.editar-perfil');
    }
}
