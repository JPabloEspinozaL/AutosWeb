<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    // CAMBIO: URLs dinámicas desde variables de entorno
    private $nodeUrl;
    private $pythonUrl;

    public function __construct()
    {
        $this->nodeUrl = env('NODE_API_URL', 'http://127.0.0.1:4000');
        $this->pythonUrl = env('PYTHON_API_URL', 'http://127.0.0.1:8001');
    }

    public function index()
    {
        // 1. VERIFICACIÓN DE SESIÓN
        if (!Session::has('usuario')) {
            return redirect('/')->withErrors(['error' => 'Debes iniciar sesión primero.']);
        }

        $usuario = Session::get('usuario');

        // 2. VERIFICACIÓN DE INTEGRIDAD (Email obligatorio)
        if (!isset($usuario['email'])) {
            Session::forget('usuario');
            return redirect('/')->withErrors(['error' => 'Tu sesión expiró. Por favor, ingresa nuevamente.']);
        }

        $autos = [];
        $ventas = [];

        // 3. CARGAR DATOS (INTENTAMOS CARGAR TODO)
        
        // A) Intentar traer VENTAS (Node.js - URL dinámica)
        try {
            $responseVentas = Http::timeout(2)->get($this->nodeUrl . '/api/ventas');
            if ($responseVentas->successful()) {
                $ventas = $responseVentas->json();
            }
        } catch (\Exception $e) {
            // Si falla, intentamos con localhost como respaldo
            try {
                $res = Http::timeout(1)->get('http://localhost:4000/api/ventas');
                if($res->successful()) $ventas = $res->json();
            } catch (\Exception $ex) {}
        }

        // B) Intentar traer AUTOS (Python - URL dinámica)
        try {
            $responseAutos = Http::timeout(2)->get($this->pythonUrl . '/vehiculos');
            if ($responseAutos->successful()) {
                $autos = $responseAutos->json();
            }
        } catch (\Exception $e) {
            // Respaldo
             try {
                $res = Http::timeout(1)->get('http://localhost:8001/vehiculos');
                if($res->successful()) $autos = $res->json();
            } catch (\Exception $ex) {}
        }

        return view('dashboard', compact('usuario', 'autos', 'ventas'));
    }

    // --- FUNCIÓN DE COMPRA ACTUALIZADA (Vendedores/Admins) ---
    public function comprar(Request $request)
    {
        $request->validate([
            'vin' => 'required',
            'cliente' => 'required|string',
            'vendedor_email' => 'required|email',
            'cliente_telefono' => 'required|string',
            'cliente_direccion' => 'required|string'
        ]);

        try {
            // Llamar a Node.js con URL dinámica
            $response = Http::post($this->nodeUrl . '/api/ventas', [
                'vendedor_email' => $request->vendedor_email,
                'cliente_nombre' => $request->cliente,
                'cliente_telefono' => $request->cliente_telefono,
                'cliente_direccion' => $request->cliente_direccion,
                'vehiculo_vin' => $request->vin
            ]);

            if ($response->successful()) {
                return back()->with('success', '¡Venta realizada con éxito! Stock actualizado.');
            } else {
                $errorMsg = $response->json()['error'] ?? 'Error desconocido';
                return back()->withErrors(['error' => 'Error: ' . $errorMsg]);
            }

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'No se pudo conectar con el sistema de Ventas (Node.js).']);
        }
    }

    // --- FUNCIONES PARA AGREGAR AUTOS (Solo Admin) ---

    public function createVehiculo()
    {
        if (Session::get('usuario')['rol'] !== 'administrador') {
            return redirect('/dashboard')->withErrors(['error' => 'Solo administradores pueden agregar autos.']);
        }
        return view('vehiculos.create');
    }

    public function storeVehiculo(Request $request)
    {
        // Validamos todos los campos del formulario
        $data = $request->validate([
            'vin' => 'required',
            'modelo' => 'required',
            'ano' => 'required|numeric',
            'precio' => 'required|numeric',
            'stock' => 'required|numeric',
            'marca_id' => 'required',
            'imagen_url' => 'required|url',
            'categoria_id' => 'required'
        ]);

        try {
            // Enviar a Python con URL dinámica
            $response = Http::post($this->pythonUrl . '/vehiculos', $data);

            if ($response->successful()) {
                return redirect('/dashboard')->with('success', '¡Vehículo agregado correctamente!');
            } else {
                return back()->withErrors(['error' => 'Error desde Python: ' . $response->body()]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'No se pudo conectar con el Inventario (Python).']);
        }
    }

    // --- FUNCIONES PARA EDITAR (CRUD: UPDATE) ---

    public function editVehiculo($vin)
    {
        if (Session::get('usuario')['rol'] !== 'administrador') {
            return redirect('/dashboard')->withErrors(['error' => 'Solo administradores pueden editar autos.']);
        }

        try {
            // Obtener TODOS los vehículos con URL dinámica
            $response = Http::get($this->pythonUrl . '/vehiculos');
            
            if ($response->successful()) {
                $autos = $response->json();
                
                // Buscar el vehículo por VIN
                $auto = null;
                foreach ($autos as $a) {
                    if ($a['vin'] === $vin) {
                        $auto = $a;
                        break;
                    }
                }
                
                if ($auto) {
                    // Mapear la marca string a ID si es necesario
                    if (!isset($auto['marca_id'])) {
                        $auto['marca_id'] = $this->mapearMarcaAId($auto['marca'] ?? '');
                    }
                    
                    return view('vehiculos.edit', compact('auto'));
                } else {
                    return redirect('/dashboard')->withErrors(['error' => 'Vehículo no encontrado.']);
                }
            } else {
                return redirect('/dashboard')->withErrors(['error' => 'Error al obtener vehículos del inventario.']);
            }
        } catch (\Exception $e) {
            return redirect('/dashboard')->withErrors(['error' => 'No se pudo conectar con el Inventario (Python).']);
        }
    }

    public function updateVehiculo(Request $request, $vin)
    {
        if (Session::get('usuario')['rol'] !== 'administrador') {
            return redirect('/dashboard')->withErrors(['error' => 'Solo administradores pueden editar autos.']);
        }

        // Validar los datos
        $data = $request->validate([
            'modelo' => 'required',
            'ano' => 'required|numeric',
            'precio' => 'required|numeric',
            'stock' => 'required|numeric',
            'marca_id' => 'required',
            'imagen_url' => 'required|url',
            'categoria_id' => 'required'
        ]);

        // Agregar el VIN a los datos
        $data['vin'] = $vin;

        try {
            // Enviar PUT a Python con URL dinámica
            $response = Http::put($this->pythonUrl . "/vehiculos/{$vin}", $data);

            if ($response->successful()) {
                return redirect('/dashboard')->with('success', '¡Vehículo actualizado correctamente!');
            } else {
                $errorMsg = $response->json()['detail'] ?? 'Error desconocido desde Python';
                return back()->withErrors(['error' => 'Error: ' . $errorMsg]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'No se pudo conectar con el Inventario (Python).']);
        }
    }

    // Método auxiliar para mapear marca string a ID
    private function mapearMarcaAId($marca)
    {
        $marcas = [
            'Ferrari' => 1,
            'Lamborghini' => 2,
            'Tesla' => 3,
            'Porsche' => 4,
            'McLaren' => 5,
            'Rolls-Royce' => 6,
            'Bugatti' => 7,
        ];
        
        return $marcas[$marca] ?? 1; // Default a Ferrari si no se encuentra
    }

    // Método auxiliar para mapear ID a marca string
    private function mapearIdAMarca($marcaId)
    {
        $marcas = [
            1 => 'Ferrari',
            2 => 'Lamborghini',
            3 => 'Tesla',
            4 => 'Porsche',
            5 => 'McLaren',
            6 => 'Rolls-Royce',
            7 => 'Bugatti',
        ];
        
        return $marcas[$marcaId] ?? 'Ferrari';
    }
}