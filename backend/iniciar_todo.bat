@echo off
echo ==================================================
echo    INICIANDO SISTEMA CONCESIONARIA SOA
echo ==================================================
echo.

:: 1. Verificar/Iniciar Docker (Intenta arrancar los contenedores si están apagados)
echo [1/4] Verificando Bases de Datos (Docker)...
docker-compose up -d

:: 2. Iniciar Backend Python (Vehículos) en una ventana nueva
echo [2/4] Iniciando Microservicio de Vehiculos (Python)...
start "Backend Python (Vehiculos)" cmd /k "cd backend\api-vehiculos-python && venv\Scripts\activate && python -m uvicorn main:app --reload --port 8001 "

:: 3. Iniciar Backend Node.js (Usuarios/Ventas) en una ventana nueva
echo [3/4] Iniciando Microservicio de Usuarios (Node.js)...
start "Backend Node (Usuarios)" cmd /k "cd backend\api-gateway-node && npx ts-node src/index.ts"

:: 4. Iniciar Frontend React en una ventana nueva
echo [4/4] Iniciando Frontend (React)...
start "Frontend Web (React)" cmd /k "cd frontend\web-laravel && php artisan serve --host=localhost --port=8000"
::php artisan serve --host=192.168.1.158 --port=8000 para acceder desde otra pc en la red o movil

echo.
echo ==================================================
echo    TODO LISTO! EL SISTEMA ESTA CORRIENDO
echo ==================================================
echo    - Python: http://localhost:8000
echo    - Node:   http://localhost:4000
echo    - React:  http://localhost:5173
echo ==================================================
pause
:: contraseña de mongo NoeO6DkxcqmVQJ47 user admin_cloud
::mongodb+srv://admin_cloud:NoeO6DkxcqmVQJ47@cluster0.m43okyu.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0
::mongodb+srv://admin_cloud:NoeO6DkxcqmVQJ47@cluster0.m43okyu.mongodb.net/concesionaria_users?retryWrites=true&w=majority&appName=Cluster0
::mongodb+srv://dockerapp:NoeO6DkxcqmVQJ47@cluster0.m43okyu.mongodb.net/concesionaria_users?retryWrites=true&w=majority&appName=Cluster0

::postgresql -- postgresql://db_concesionaria_user:CJIdDvzyGoBziH1HfSE6H5H99cMm0U0X@dpg-d4l12gshg0os73b15hk0-a.oregon-postgres.render.com/
:: urlrender 
::https://api-vehiculos-python.onrender.com --python
::https://backendnode-2.onrender.com --node
