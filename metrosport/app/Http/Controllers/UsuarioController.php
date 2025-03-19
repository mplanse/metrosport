<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuari;

class UsuarioController extends Controller
{
    // Mostrar la vista de login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Procesar el login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nom_usuari' => 'required|string',
            'contrasenya' => 'required|string',
        ]);

        // Buscar usuario por nombre de usuario
        $usuario = Usuari::where('nom_usuari', $credentials['nom_usuari'])->first();

        // Validar credenciales
        if (!$usuario || !Hash::check($credentials['contrasenya'], $usuario->contrasenya)) {
            return back()->withErrors(['error' => 'Usuario o contraseña incorrectos']);
        }

        // Autenticar usuario
        Auth::login($usuario);
        return redirect()->route('lligues.index');
    }

    // Mostrar la vista de registro
    public function showRegister()
    {
        return view('auth.register');
    }

    // Procesar el registro
    public function register(Request $request)
    {
        $request->validate([
            'nom_usuari' => 'required|string|unique:usuari,nom_usuari',
            'mail' => 'required|email|unique:usuari,mail',
            'contrasenya' => 'required|string|min:6|confirmed',
        ]);

        // Crear usuario con contraseña encriptada
        $usuario = Usuari::create([
            'nom_usuari' => $request->nom_usuari,
            'mail' => $request->mail,
            'contrasenya' => Hash::make($request->contrasenya), // Encriptar la contraseña
        ]);

        // Autenticar automáticamente al usuario después del registro
        Auth::login($usuario);

        return redirect()->route('lligues.index');
    }

    // Cerrar sesión
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
