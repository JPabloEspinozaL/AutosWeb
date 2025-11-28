<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Concesionaria SOA</title>
    <script src="https://cdn.tailwindcss.com"></script>
  @laravelPWA
</head>
<body class="bg-gray-800 h-screen flex justify-center items-center">

    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold text-center mb-6 text-gray-700">Iniciar Sesión 🔐</h2>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Correo Electrónico</label>
                <input type="email" name="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="admin@test.com">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Contraseña</label>
                <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="******">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition duration-300">
                Entrar
            </button>
        </form>
    </div>

</body>
</html>