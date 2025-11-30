<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    // Variable para guardar la URL base de la API de Node
    private $nodeUrl;

    public function __construct()
    {
        // Si existe la variable de entorno NODE_API_URL (en Render), usa esa.
        // Si no (en tu PC), usa localhost:4000.
        $this->nodeUrl = env('NODE_API_URL', 'http://127.0.0.1:4000');
    }

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
            'nombre'   => 'required|string|min:3',
            'email'    => 'required|email',
            'password' => 'required|min:3',
            'rol'      => 'required|in:administrador,vendedor,consultor'
        ]);

        try {
            // USAMOS LA VARIABLE DINÁMICA AQUÍ
            // Esto se convierte en: https://tu-api-node.onrender.com/api/usuarios
            $response = Http::post($this->nodeUrl . '/api/usuarios', [
                'nombre'   => $request->nombre,
                'email'    => $request->email,
                'password' => $request->password,
                'rol'      => $request->rol
            ]);

            if ($response->successful()) {
                return redirect('/dashboard')->with('success', '¡Usuario registrado correctamente!');
            } else {
                // Si Node responde error (ej. correo duplicado)
                $errorMsg = $response->json()['error'] ?? 'Error al registrar.';
                return back()->withErrors(['error' => $errorMsg]);
            }

        } catch (\Exception $e) {
            // Es útil loguear el error real para debugging interno
            // \Log::error($e->getMessage()); 
            return back()->withErrors(['error' => 'No se pudo conectar con el servidor de Usuarios (Node.js).']);
        }
    }
}