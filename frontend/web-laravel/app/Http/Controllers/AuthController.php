<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Cliente HTTP para llamar a Node
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // 1. Mostrar el formulario de Login (VISTA)
    public function showLoginForm()
    {
        // Si ya está logueado, lo mandamos al dashboard directo
        if (Session::has('usuario')) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    // 2. Procesar el Login (CONTROLADOR -> NODE.JS)
    public function login(Request $request)
    {
        // Validamos que los campos no vengan vacíos
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            // --- CONEXIÓN CON TU MICROSERVICIO NODE.JS (RF02) ---
            // Asegúrate de poner la IP correcta si estás en redes distintas (ej. 192.168...)
            // Si es la misma PC, usa localhost.
            $response = Http::post('http://localhost:4000/api/login', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            // Si Node responde "Login exitoso" (Status 200)
            if ($response->successful()) {
                $data = $response->json();
                
                // Guardamos al usuario en la Sesión de Laravel
                // Esto mantiene al usuario "logueado" mientras navega
                Session::put('usuario', $data['usuario']);
                
                return redirect('/dashboard');
            } else {
                // Si Node responde error (400/401)
                return back()->withErrors(['error' => 'Credenciales incorrectas o usuario no encontrado.']);
            }

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error de conexión con el servidor de Usuarios (Node.js).']);
        }
    }

    // 3. Cerrar Sesión
    public function logout()
    {
        Session::forget('usuario');
        return redirect('/');
    }
}