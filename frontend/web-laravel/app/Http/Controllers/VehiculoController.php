<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Cliente para llamar a tus APIs

class VehiculoController extends Controller
{
    // Variable para guardar la URL base de la API de Python
    private $pythonUrl;

    public function __construct()
    {
        // Si existe la variable PYTHON_API_URL (en Render), usa esa.
        // Si no, usa localhost:8001 (o 8000, según como lo tengas localmente).
        $this->pythonUrl = env('PYTHON_API_URL', 'http://127.0.0.1:8001');
    }

    public function index()
    {
        // 1. Llamamos a tu Microservicio de Python usando la URL dinámica
        try {
            // USAMOS LA VARIABLE DINÁMICA AQUÍ
            // Esto se convierte en: https://api-vehiculos-python.onrender.com/vehiculos
            $respuesta = Http::get($this->pythonUrl . '/vehiculos');
            
            if ($respuesta->successful()) {
                $autos = $respuesta->json(); // Convertimos JSON a Array de PHP
            } else {
                $autos = []; // Si la API responde pero con error (ej. 404 o 500)
            }
            
        } catch (\Exception $e) {
            $autos = []; // Si falla la conexión, mostramos lista vacía
        }

        // 2. Enviamos los datos a la VISTA (Blade)
        return view('vehiculos.index', compact('autos'));
    }
}