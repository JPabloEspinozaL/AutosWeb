<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Cliente para llamar a tus APIs

class VehiculoController extends Controller
{
    public function index()
    {
        // 1. Llamamos a tu Microservicio de Python (IP de la PC servidor)
        // Si está en la misma PC usa localhost, si es otra usa la IP 192...
        try {
            $respuesta = Http::get('http://127.0.0.1:8000/vehiculos');
            $autos = $respuesta->json(); // Convertimos JSON a Array de PHP
        } catch (\Exception $e) {
            $autos = []; // Si falla, mostramos lista vacía para que no explote
        }

        // 2. Enviamos los datos a la VISTA (Blade)
        return view('vehiculos.index', compact('autos'));
    }
}
