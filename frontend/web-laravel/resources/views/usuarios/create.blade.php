<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Usuario | Panel Administrativo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Registrar Nuevo Usuario 👤
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Crea una cuenta para administradores, vendedores o consultores.
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="/dashboard" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    ← Volver al Dashboard
                </a>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
            <div class="p-8">
                
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

                <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-8 divide-y divide-gray-200">
                    @csrf
                    
                    <div class="space-y-6 pt-4">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Datos de la Cuenta</h3>
                            <p class="mt-1 text-sm text-gray-500">Información necesaria para el acceso al sistema.</p>
                        </div>

                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            
                            <div class="sm:col-span-3">
                                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                                <div class="mt-1">
                                    <input type="text" name="nombre" id="nombre" required placeholder="Ej: Juan Pérez" 
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border">
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="rol" class="block text-sm font-medium text-gray-700">Rol de Usuario</label>
                                <div class="mt-1">
                                    <select id="rol" name="rol" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border bg-white">
                                        <option value="vendedor">Vendedor</option>
                                        <option value="consultor">Consultor</option>
                                        <option value="administrador">Administrador</option>
                                    </select>
                                </div>
                            </div>

                            <div class="sm:col-span-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                                <div class="mt-1">
                                    <input type="email" name="email" id="email" required placeholder="usuario@empresa.com" 
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border">
                                </div>
                            </div>

                            <div class="sm:col-span-4">
                                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                                <div class="mt-1">
                                    <input type="password" name="password" id="password" required 
                                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Mínimo 3 caracteres.</p>
                            </div>

                        </div>
                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end">
                            <a href="/dashboard" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancelar
                            </a>
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Registrar Usuario
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>