<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Vehículo | Panel Administrativo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        
        <!-- Encabezado -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Editar Vehículo 🏎️
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Actualiza la información del vehículo en el inventario global (Python Microservice).
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="/dashboard" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    ← Volver al Dashboard
                </a>
            </div>
        </div>

        <!-- Tarjeta del Formulario -->
        <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
            <div class="p-8">
                
                <!-- Alertas de Error -->
                @if($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Hay errores en el formulario</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('vehiculos.update', $auto['vin']) }}" method="POST" class="space-y-8 divide-y divide-gray-200">
                    @csrf
                    @method('PUT')
                    
                    <!-- Sección 1: Datos Principales -->
                    <div class="space-y-6 pt-4">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Vehículo</h3>
                            <p class="mt-1 text-sm text-gray-500">Datos técnicos básicos para el catálogo.</p>
                        </div>

                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- VIN -->
                            <div class="sm:col-span-3">
                                <label for="vin" class="block text-sm font-medium text-gray-700">VIN (Código Único)</label>
                                <div class="mt-1">
                                    <input type="text" name="vin" id="vin" value="{{ $auto['vin'] }}" readonly
                                           class="shadow-sm bg-gray-100 block w-full sm:text-sm border-gray-300 rounded-md p-2 border cursor-not-allowed">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">El VIN no puede ser modificado.</p>
                            </div>

                            <!-- Modelo -->
                            <div class="sm:col-span-3">
                                <label for="modelo" class="block text-sm font-medium text-gray-700">Modelo</label>
                                <div class="mt-1">
                                    <input type="text" name="modelo" id="modelo" required placeholder="Ej: SF90 Stradale" 
                                           value="{{ $auto['modelo'] }}"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border">
                                </div>
                            </div>

                            <!-- Marca -->
                            <div class="sm:col-span-3">
                                <label for="marca_id" class="block text-sm font-medium text-gray-700">Marca</label>
                                <div class="mt-1">
                                    <select id="marca_id" name="marca_id" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border bg-white">
                                        <option value="1" {{ ($auto['marca_id'] ?? 1) == 1 ? 'selected' : '' }}>Ferrari 🇮🇹</option>
                                        <option value="2" {{ ($auto['marca_id'] ?? 1) == 2 ? 'selected' : '' }}>Lamborghini 🇮🇹</option>
                                        <option value="3" {{ ($auto['marca_id'] ?? 1) == 3 ? 'selected' : '' }}>Tesla 🇺🇸</option>
                                        <option value="4" {{ ($auto['marca_id'] ?? 1) == 4 ? 'selected' : '' }}>Porsche 🇩🇪</option>
                                        <option value="5" {{ ($auto['marca_id'] ?? 1) == 5 ? 'selected' : '' }}>McLaren 🇬🇧</option>
                                        <option value="6" {{ ($auto['marca_id'] ?? 1) == 6 ? 'selected' : '' }}>Rolls-Royce 🇬🇧</option>
                                        <option value="7" {{ ($auto['marca_id'] ?? 1) == 7 ? 'selected' : '' }}>Bugatti 🇫🇷</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Año -->
                            <div class="sm:col-span-3">
                                <label for="ano" class="block text-sm font-medium text-gray-700">Año de Fabricación</label>
                                <div class="mt-1">
                                    <input type="number" name="ano" id="ano" value="{{ $auto['ano'] }}" min="1900" max="2026"
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 2: Detalles de Venta -->
                    <div class="space-y-6 pt-8">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Detalles de Venta</h3>
                            <p class="mt-1 text-sm text-gray-500">Precios y disponibilidad.</p>
                        </div>

                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- Precio -->
                            <div class="sm:col-span-3">
                                <label for="precio" class="block text-sm font-medium text-gray-700">Precio de Lista ($)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="precio" id="precio" step="0.01" required placeholder="0.00" 
                                           value="{{ $auto['precio'] }}"
                                           class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md p-2 border">
                                </div>
                            </div>

                            <!-- Stock -->
                            <div class="sm:col-span-3">
                                <label for="stock" class="block text-sm font-medium text-gray-700">Stock Disponible</label>
                                <div class="mt-1">
                                    <input type="number" name="stock" id="stock" value="{{ $auto['stock'] }}" min="0" required
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border">
                                </div>
                            </div>

                            <!-- Imagen URL -->
                            <div class="sm:col-span-6">
                                <label for="imagen_url" class="block text-sm font-medium text-gray-700">URL de la Imagen</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                        🔗
                                    </span>
                                    <input type="url" name="imagen_url" id="imagen_url" required placeholder="https://ejemplo.com/auto.jpg" 
                                           value="{{ $auto['imagen_url'] }}"
                                           class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300 p-2 border"
                                           oninput="previewImage(this.value)">
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Pega el enlace directo a una imagen. La vista previa aparecerá abajo.</p>
                                
                                <!-- ÁREA DE VISTA PREVIA -->
                                <div id="image-preview-container" class="mt-4 p-4 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 flex flex-col justify-center items-center">
                                    <p class="text-xs text-gray-500 mb-2">Vista Previa:</p>
                                    <img id="image-preview" src="{{ $auto['imagen_url'] }}" alt="Vista previa" class="max-h-64 rounded shadow-lg object-contain">
                                    <p id="image-error" class="hidden text-red-500 text-xs mt-2">❌ No se pudo cargar la imagen. Verifica el enlace.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Campos Ocultos -->
                    <input type="hidden" name="categoria_id" value="{{ $auto['categoria_id'] ?? 1 }}">

                    <!-- Botones de Acción -->
                    <div class="pt-5">
                        <div class="flex justify-end">
                            <a href="/dashboard" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancelar
                            </a>
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Actualizar Vehículo
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script para Previsualizar Imagen -->
    <script>
        function previewImage(url) {
            const container = document.getElementById('image-preview-container');
            const img = document.getElementById('image-preview');
            const errorMsg = document.getElementById('image-error');
            
            // Reiniciar estados
            errorMsg.classList.add('hidden');
            img.classList.remove('hidden');

            if (url && url.length > 5) {
                container.classList.remove('hidden');
                img.src = url;
                
                // Manejar error de carga
                img.onerror = function() {
                    img.classList.add('hidden');
                    errorMsg.classList.remove('hidden');
                };
            } else {
                container.classList.add('hidden');
            }
        }

        // Inicializar vista previa con la imagen actual
        document.addEventListener('DOMContentLoaded', function() {
            previewImage('{{ $auto['imagen_url'] }}');
        });
    </script>
</body>
</html>