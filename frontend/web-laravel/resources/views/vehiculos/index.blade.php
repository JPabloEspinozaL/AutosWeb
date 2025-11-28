<!DOCTYPE html>
<html>
<head>
    <title>Concesionaria - Inventario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <h1 class="text-3xl font-bold mb-5">Inventario de Lujo (Desde Laravel + Python)</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @forelse($autos as $auto)
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-xl font-bold text-red-600">{{ $auto['marca'] }}</h2>
                <p class="text-lg">{{ $auto['modelo'] }} ({{ $auto['ano'] }})</p>
                <p class="text-gray-600">Stock: {{ $auto['stock'] }}</p>
                <p class="font-bold mt-2 text-green-600">${{ number_format($auto['precio'], 2) }}</p>
            </div>
        @empty
            <p class="text-red-500">No hay conexión con el servidor de Python.</p>
        @endforelse
    </div>
</body>
</html>