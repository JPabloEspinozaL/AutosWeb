<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    // 1. Mostrar formulario de registro
    public function create()
    {
        // Solo el admin puede ver esto
        if (Session::get('usuario')['rol'] !== 'administrador') {
            return redirect('/dashboard')->withErrors(['error' => 'Acceso denegado.']);
        }
        return view('usuarios.create');
    }

    // 2. Enviar datos a Node.js para crear el usuario
    public function store(Request $request)
    {
        // Validamos los datos en Laravel
        $request->validate([
            'nombre' => 'required|string|min:3',
            'email' => 'required|email',
            'password' => 'required|min:3',
            'rol' => 'required|in:administrador,vendedor,consultor'
        ]);

        try {
            // Enviamos la petición a Node.js (Puerto 4000)
            $response = Http::post('http://127.0.0.1:4000/api/usuarios', [
                'nombre' => $request->nombre,
                'email' => $request->email,
                'password' => $request->password,
                'rol' => $request->rol
            ]);

            if ($response->successful()) {
                return redirect('/dashboard')->with('success', '¡Usuario registrado correctamente!');
            } else {
                // Si Node responde error (ej. correo duplicado)
                $errorMsg = $response->json()['error'] ?? 'Error al registrar.';
                return back()->withErrors(['error' => $errorMsg]);
            }

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'No se pudo conectar con el servidor de Usuarios (Node.js).']);
        }
    }
}