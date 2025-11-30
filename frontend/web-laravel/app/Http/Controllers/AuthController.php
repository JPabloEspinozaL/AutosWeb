<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    
    private $nodeUrl;
    private $pythonUrl;

    public function __construct()
    {
        $this->nodeUrl = env('NODE_API_URL', 'http://127.0.0.1:4000');
        $this->pythonUrl = env('PYTHON_API_URL', 'http://127.0.0.1:8001');
    }

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
           // Verificación temporal (luego quitar)
    \Log::info("Conectando a Node.js: " . $this->nodeUrl);
    \Log::info("Conectando a Python: " . $this->pythonUrl);
    
        // Validamos que los campos no vengan vacíos
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            // --- CONEXIÓN CON TU MICROSERVICIO NODE.JS (RF02) ---
            // Usamos la variable de entorno con timeout
            $response = Http::timeout(10)->post($this->nodeUrl . '/api/login', [
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
            return back()->withErrors(['error' => 'Error de conexión con el servidor de Usuarios (Node.js): ' . $e->getMessage()]);
        }
    }

    // 3. Cerrar Sesión
    public function logout()
    {
        Session::forget('usuario');
        return redirect('/');
    }
}