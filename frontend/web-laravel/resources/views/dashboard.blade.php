<!DOCTYPE html>
<html lang="es">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control | Concesionaria SOA</title>
    
    <!-- 1. ESTILOS (Tailwind CSS) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- 2. FUENTES -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }

        /* ESTILOS EXCLUSIVOS PARA IMPRESIÓN (SOLUCIÓN ANCHO COMPLETO) */
        @media print {
            /* Configuración de la hoja */
            @page { margin: 1cm; size: letter; }

            /* Ocultar elementos innecesarios */
            nav, .no-print, button, a, footer { display: none !important; }
            
            /* Resetear estilos del cuerpo */
            body { 
                background-color: white; 
                color: black; 
                margin: 0; 
                padding: 0; 
                -webkit-print-color-adjust: exact; 
            }
            
            /* FORZAR ANCHO COMPLETO (Anula max-w-7xl de Tailwind) */
            main { 
                width: 100% !important; 
                max-width: none !important; 
                margin: 0 !important; 
                padding: 0 !important; 
                display: block !important; 
            }

            /* Encabezado del reporte */
            .print-header { 
                display: block !important; 
                width: 100%; 
                text-align: center; 
                margin-bottom: 20px; 
                border-bottom: 2px solid black; 
                padding-bottom: 10px; 
            }
            
            /* Tabla ocupando todo el espacio */
            .print-container { 
                width: 100% !important; 
                margin: 0 !important; 
                box-shadow: none !important; 
                border: none !important; 
            }
            
            /* Estilos de celdas para impresión nítida */
            table { width: 100% !important; border-collapse: collapse; font-size: 11pt; }
            th, td { border: 1px solid #000 !important; padding: 6px 4px; text-align: center; }
            th { background-color: #e5e7eb !important; font-weight: bold; color: black !important; }
            
            /* Alinear montos a la derecha */
            td:last-child, th:last-child { text-align: right; padding-right: 8px; }
        }
        
        /* Ocultar el encabezado de impresión en la pantalla normal */
        .print-header { display: none; }

        /* Estilos para el modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background: white;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
    </style>
    <!-- PWA -->
    @laravelPWA
<!-- PWA CONFIGURACIÓN MANUAL -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <link rel="apple-touch-icon" href="https://cdn-icons-png.flaticon.com/512/3085/3085330.png">

    <!-- Script para registrar la PWA -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((reg) => console.log('PWA Service Worker registrado', reg))
                    .catch((err) => console.log('Error PWA', err));
            });
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navbar Superior (Se ocultará al imprimir) -->
    <nav class="bg-white shadow-md border-b border-gray-200 sticky top-0 z-50 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <span class="text-2xl">🏎️</span>
                        <h1 class="font-bold text-xl tracking-tight text-gray-900">
                            Concesionaria <span class="text-indigo-600">SOA</span>
                        </h1>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium text-gray-900">{{ $usuario['nombre'] }}</p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 capitalize">
                            {{ $usuario['rol'] }}
                        </span>
                    </div>
                    <a href="{{ route('logout') }}" class="bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- ENCABEZADO DE REPORTE (Solo visible al imprimir) -->
        <div class="print-header">
            <h1 style="font-size: 24px; font-weight: bold; margin: 0;">CONCESIONARIA SOA</h1>
            <h2 style="font-size: 16px; margin: 5px 0 15px 0; color: #333;">Reporte Oficial de Ventas y Transacciones</h2>
            <div style="display: flex; justify-content: space-between; font-size: 11px; border-top: 1px solid #000; padding-top: 8px;">
                <span><strong>Generado por:</strong> {{ $usuario['nombre'] }} ({{ strtoupper($usuario['rol']) }})</span>
                <span><strong>Fecha de Impresión:</strong> {{ date('d/m/Y H:i:s') }}</span>
            </div>
        </div>

        <!-- Mensajes de Alerta -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between no-print">
                <div class="flex items-center">
                    <p class="ml-3 text-sm font-medium text-green-700">✅ {{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm no-print">
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Error</h3>
                    <p class="text-sm text-red-700 mt-1">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        <!-- ========================================================= -->
        <!-- SECCIÓN 1: REPORTE DE VENTAS (Consultor y Admin)          -->
        <!-- ========================================================= -->
        @if($usuario['rol'] === 'consultor' || $usuario['rol'] === 'administrador')
            
            <div class="flex justify-between items-end mb-6 mt-4 no-print">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">📊 Reporte de Ventas Globales</h2>
                    <p class="text-sm text-gray-500">Datos en tiempo real desde Node.js (Puerto 4000)</p>
                </div>
                <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-black transition flex items-center gap-2 cursor-pointer">
                    🖨️ Imprimir Reporte
                </button>
            </div>

            <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200 mb-12 print-container">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teléfono</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dirección</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehículo (VIN)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendedor</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($ventas) && count($ventas) > 0)
                            @foreach($ventas as $venta)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ date('d/m/Y H:i', strtotime($venta['fecha'])) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    {{ $venta['cliente_nombre'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $venta['cliente_telefono'] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $venta['cliente_direccion'] ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 no-print">
                                        {{ $venta['vehiculo_vin'] }}
                                    </span>
                                    <span class="print-only" style="display:none">{{ $venta['vehiculo_vin'] }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $venta['vendedor_email'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-green-600">
                                    ${{ number_format($venta['total'], 2) }}
                                </td>
                            </tr>
                            @endforeach
                            <!-- TOTAL FINAL (Visible en Reporte) -->
                            <tr style="background-color: #f8f9fa; font-weight: bold; border-top: 2px solid black;">
                                <td colspan="6" class="px-6 py-4 text-right">TOTAL ACUMULADO:</td>
                                <td class="px-6 py-4 text-right text-green-700" style="font-size: 14px;">
                                    ${{ number_format(array_sum(array_column($ventas, 'total')), 2) }}
                                </td>
                            </tr>
                        @else
                            <tr><td colspan="7" class="px-6 py-10 text-center text-gray-500">No hay ventas registradas.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            @if($usuario['rol'] === 'administrador')
                <hr class="border-gray-300 my-10 no-print">
            @endif
        @endif


        <!-- ========================================================= -->
        <!-- SECCIÓN 2: CATÁLOGO DE AUTOS (Vendedor y Admin)           -->
        <!-- ========================================================= -->
        <!-- Todo esto tendrá la clase no-print para que NO salga en el papel -->
        @if($usuario['rol'] !== 'consultor')
            
            <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4 no-print">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Vehículos Disponibles</h2>
                    <p class="mt-1 text-sm text-gray-500">Inventario desde Python (Puerto 8001).</p>
                </div>
                
               @if($usuario['rol'] === 'administrador')
    <div class="flex gap-2">
        <!-- Botón Auto -->
        <a href="{{ route('vehiculos.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2 shadow-md transform hover:-translate-y-0.5">
            <span>🚗</span> Nuevo Auto
        </a>

        <!-- Botón Usuario (NUEVO) -->
        <a href="{{ route('usuarios.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center gap-2 shadow-md transform hover:-translate-y-0.5">
            <span>👤</span> Nuevo Usuario
        </a>
    </div>
@endif
            </div>

            @if(empty($autos))
                <div class="text-center py-20 bg-white rounded-xl shadow-sm border border-dashed border-gray-300 no-print">
                    <div class="text-5xl mb-4">🔌</div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Sin conexión al inventario</h3>
                    <p class="mt-1 text-sm text-gray-500">Verifique que Python esté corriendo en el puerto 8001.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 no-print">
                    @foreach($autos as $auto)
                        <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col">
                            
                            <!-- Imagen -->
                            <div class="relative h-56 overflow-hidden bg-gray-200">
                                <img src="{{ $auto['imagen_url'] ?? 'https://via.placeholder.com/800x600?text=Auto' }}" 
                                     alt="{{ $auto['modelo'] }}" 
                                     class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                
                                <div class="absolute top-4 right-4">
                                    @if($auto['stock'] > 0)
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 shadow-sm border border-green-200">
                                            🟢 {{ $auto['stock'] }} Disp.
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 shadow-sm border border-red-200">
                                            🔴 Agotado
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="mb-4">
                                    <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider">{{ $auto['marca'] }}</h3>
                                    <h4 class="text-xl font-bold text-gray-900 mt-1">{{ $auto['modelo'] }}</h4>
                                </div>

                                <div class="flex items-end justify-between mb-6 pb-6 border-b border-gray-100">
                                    <div>
                                        <p class="text-xs text-gray-400">Precio</p>
                                        <p class="text-2xl font-bold text-gray-900">${{ number_format($auto['precio']) }}</p>
                                    </div>
                                    <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ $auto['ano'] }}</span>
                                </div>

                                <!-- Botón para abrir modal de venta -->
                                <div class="mt-auto space-y-2">
                                    @if($auto['stock'] > 0)
                                        <button onclick="openSaleModal({{ json_encode($auto) }})" 
                                                class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition flex justify-center items-center gap-2 shadow-sm">
                                            <span>💰</span> Vender Este Auto
                                        </button>
                                    @else
                                        <button disabled class="w-full bg-gray-100 text-gray-400 font-bold py-3 rounded-lg cursor-not-allowed border border-gray-200">
                                            🚫 No Disponible
                                        </button>
                                    @endif

                                    <!-- BOTÓN EDITAR (SOLO PARA ADMIN) -->
                                    @if($usuario['rol'] === 'administrador')
                                        <div class="flex gap-2 mt-2">
                                            <a href="{{ route('vehiculos.edit', $auto['vin']) }}" class="flex-1 bg-yellow-500 text-white text-center py-2 rounded hover:bg-yellow-600 transition flex items-center justify-center gap-1">
                                                <span>✏️</span> Editar
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

    </main>

    <!-- Footer -->
    <footer class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-sm text-gray-400 no-print">
        &copy; {{ date('Y') }} Sistema de Concesionaria SOA. Microservicios activos.
    </footer>

    <!-- MODAL DE VENTA -->
    <div id="saleModal" class="modal no-print">
        <div class="modal-content">
            <div class="p-6">
                <!-- Encabezado del Modal -->
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">📝 Procesar Venta</h3>
                    <button onclick="closeSaleModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                        &times;
                    </button>
                </div>

                <!-- Información del Vehículo -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h4 class="font-semibold text-blue-800 mb-2">Vehículo Seleccionado</h4>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div><span class="font-medium">Modelo:</span> <span id="modalModelo"></span></div>
                        <div><span class="font-medium">Marca:</span> <span id="modalMarca"></span></div>
                        <div><span class="font-medium">Año:</span> <span id="modalAno"></span></div>
                        <div><span class="font-medium">VIN:</span> <span id="modalVin" class="font-mono"></span></div>
                        <div><span class="font-medium">Precio:</span> <span id="modalPrecio" class="font-bold text-green-600"></span></div>
                        <div><span class="font-medium">Stock:</span> <span id="modalStock"></span></div>
                    </div>
                </div>

                <!-- Formulario de Datos del Cliente -->
                <form id="saleForm" action="{{ route('venta.post') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="vin" id="formVin">
                    <input type="hidden" name="vendedor_email" value="{{ $usuario['email'] ?? 'admin@test.com' }}">

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h4 class="font-semibold text-gray-800 mb-3">👤 Datos del Comprador</h4>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo *</label>
                                <input type="text" name="cliente" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                                       placeholder="Ej: Juan Pérez García">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                                <input type="text" name="cliente_telefono" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                                       placeholder="Ej: +52 55 1234 5678">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección *</label>
                                <input type="text" name="cliente_direccion" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                                       placeholder="Ej: Av. Principal #123, Col. Centro, CDMX">
                            </div>
                        </div>
                    </div>

                    <!-- Resumen de Venta -->
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <h4 class="font-semibold text-green-800 mb-2">💰 Resumen de Venta</h4>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total a pagar:</span>
                            <span id="modalTotal" class="text-xl font-bold text-green-600"></span>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeSaleModal()" 
                                class="flex-1 bg-gray-300 text-gray-700 font-medium py-3 rounded-lg hover:bg-gray-400 transition">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="flex-1 bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                            <span>✅</span> Confirmar Venta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript para el Modal -->
    <script>
        function openSaleModal(auto) {
            // Llenar información del vehículo
            document.getElementById('modalModelo').textContent = auto.modelo;
            document.getElementById('modalMarca').textContent = auto.marca;
            document.getElementById('modalAno').textContent = auto.ano;
            document.getElementById('modalVin').textContent = auto.vin;
            document.getElementById('modalPrecio').textContent = '$' + new Intl.NumberFormat().format(auto.precio);
            document.getElementById('modalStock').textContent = auto.stock + ' unidades';
            document.getElementById('modalTotal').textContent = '$' + new Intl.NumberFormat().format(auto.precio);
            
            // Llenar campos del formulario
            document.getElementById('formVin').value = auto.vin;
            
            // Mostrar modal
            document.getElementById('saleModal').classList.add('active');
            
            // Enfocar el primer campo del formulario
            setTimeout(() => {
                document.querySelector('input[name="cliente"]').focus();
            }, 300);
        }

        function closeSaleModal() {
            document.getElementById('saleModal').classList.remove('active');
            // Limpiar formulario
            document.getElementById('saleForm').reset();
        }

        // Cerrar modal al hacer clic fuera del contenido
        document.getElementById('saleModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeSaleModal();
            }
        });

        // Cerrar modal con tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSaleModal();
            }
        });

        // Prevenir envío doble del formulario
        document.getElementById('saleForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>⏳</span> Procesando...';
        });
    </script>

</body>

</html>