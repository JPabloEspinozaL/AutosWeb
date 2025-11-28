import os  # NUEVO: Importar para variables de entorno
from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from sqlalchemy import create_engine, text
from fastapi.middleware.cors import CORSMiddleware

app = FastAPI()

# Configuración de CORS para que Laravel y Node puedan conectarse
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# --- CONFIGURACIÓN ---
# CAMBIO 1: Conexión a PostgreSQL dinámica (usa variable de entorno)
DATABASE_URL = os.getenv(
    "DATABASE_URL", 
    "postgresql://admin:password123@localhost:5432/concesionaria_vehiculos"
)

# IMPORTANTE: Render y algunos servicios usan 'postgres://' pero SQLAlchemy necesita 'postgresql://'
if DATABASE_URL.startswith("postgres://"):
    DATABASE_URL = DATABASE_URL.replace("postgres://", "postgresql://", 1)

engine = create_engine(DATABASE_URL)

# --- MODELOS DE DATOS ---
class VehiculoCrear(BaseModel):
    vin: str
    modelo: str
    ano: int
    precio: float
    stock: int
    marca_id: int
    categoria_id: int
    imagen_url: str = "https://via.placeholder.com/300"

# --- NUEVO MODELO PARA MARCAS ---
class MarcaCrear(BaseModel):
    nombre: str
    pais_origen: str

# --- RUTAS ---

@app.get("/")
def home():
    return {"mensaje": "API de Vehículos (Python) Lista 🏎️"}

# 1. Consultar todo el inventario (RF09)
@app.get("/vehiculos")
def listar_vehiculos():
    try:
        with engine.connect() as conn:
            query = """
            SELECT v.vin, v.modelo, v.ano, v.precio, v.stock, v.imagen_url, m.nombre as marca 
            FROM vehiculos v 
            JOIN marcas m ON v.marca_id = m.marca_id
            ORDER BY v.stock DESC
            """
            result = conn.execute(text(query))
            return [dict(row._mapping) for row in result]
    except Exception as e:
        print(f"Error SQL: {e}")
        return []

# 2. Registrar un nuevo auto (RF10)
@app.post("/vehiculos")
def crear_vehiculo(auto: VehiculoCrear):
    try:
        with engine.connect() as conn:
            query = text("""
            INSERT INTO vehiculos (vin, modelo, ano, precio, stock, marca_id, categoria_id, imagen_url)
            VALUES (:vin, :modelo, :ano, :precio, :stock, :marca, :cat, :img)
            """)
            
            conn.execute(query, {
                "vin": auto.vin,
                "modelo": auto.modelo,
                "ano": auto.ano,
                "precio": auto.precio,
                "stock": auto.stock,
                "marca": auto.marca_id,
                "cat": auto.categoria_id,
                "img": auto.imagen_url
            })
            conn.commit()
            
        return {"mensaje": "Vehículo registrado exitosamente", "auto": auto.modelo}
        
    except Exception as e:
        raise HTTPException(status_code=400, detail=f"Error al guardar: {str(e)}")

# 3. Actualizar stock por venta (Llamado por Node.js)
@app.post("/vehiculos/{vin}/restar-stock")
def restar_stock(vin: str):
    print(f"Recibida petición para restar stock al VIN: {vin}")
    try:
        with engine.connect() as conn:
            # 1. Verificamos si existe y el stock actual
            result = conn.execute(text("SELECT stock FROM vehiculos WHERE vin = :vin"), {"vin": vin})
            row = result.fetchone()
            
            if not row:
                raise HTTPException(status_code=404, detail="Auto no encontrado")
                
            stock_actual = row[0]

            if stock_actual <= 0:
                raise HTTPException(status_code=400, detail="No hay stock suficiente")
            
            # 2. Ejecutamos la resta
            conn.execute(text("UPDATE vehiculos SET stock = stock - 1 WHERE vin = :vin"), {"vin": vin})
            conn.commit()
            
        return {"mensaje": f"Stock actualizado. Quedan {stock_actual - 1} unidades."}
        
    except Exception as e:
        print(f"Excepción: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))

# --- GESTIÓN DE MARCAS (NUEVO) ---

# 4. Listar Marcas (Para llenar selects en el frontend)
@app.get("/marcas")
def listar_marcas():
    try:
        with engine.connect() as conn:
            # Ordenamos por nombre para que sea más fácil buscar en el select
            query = "SELECT * FROM marcas ORDER BY nombre ASC"
            result = conn.execute(text(query))
            return [dict(row._mapping) for row in result]
    except Exception as e:
        print(f"Error al listar marcas: {e}")
        return []

# 5. Crear Nueva Marca
@app.post("/marcas")
def crear_marca(marca: MarcaCrear):
    try:
        with engine.connect() as conn:
            query = text("INSERT INTO marcas (nombre, pais_origen) VALUES (:nombre, :pais)")
            conn.execute(query, {"nombre": marca.nombre, "pais": marca.pais_origen})
            conn.commit()
        return {"mensaje": "Marca creada exitosamente", "marca": marca.nombre}
    except Exception as e:
        # Capturamos el error de integridad (si la marca ya existe por el constraint UNIQUE)
        raise HTTPException(status_code=400, detail=f"Error al crear marca (posible duplicado): {str(e)}")
    
# 6. Modificar Marca (Nuevo Requerimiento)
@app.put("/marcas/{marca_id}")
def modificar_marca(marca_id: int, marca: MarcaCrear):
    try:
        with engine.connect() as conn:
            # Verificar si existe primero
            check = conn.execute(text("SELECT marca_id FROM marcas WHERE marca_id = :id"), {"id": marca_id})
            if not check.fetchone():
                raise HTTPException(status_code=404, detail="Marca no encontrada")

            # Actualizar
            query = text("UPDATE marcas SET nombre = :nombre, pais_origen = :pais WHERE marca_id = :id")
            conn.execute(query, {"nombre": marca.nombre, "pais": marca.pais_origen, "id": marca_id})
            conn.commit()
            
        return {"mensaje": "Marca actualizada exitosamente"}
    except Exception as e:
        raise HTTPException(status_code=400, detail=f"Error al actualizar marca: {str(e)}")

# --- ACTUALIZAR VEHÍCULO (CRUD: UPDATE) ---
@app.put("/vehiculos/{vin}")
def actualizar_vehiculo(vin: str, auto: VehiculoCrear):
    try:
        with engine.connect() as conn:
            # 1. Verificar que existe
            check = conn.execute(text("SELECT vin FROM vehiculos WHERE vin = :vin"), {"vin": vin})
            if not check.fetchone():
                raise HTTPException(status_code=404, detail="Vehículo no encontrado")

            # 2. Actualizar datos
            query = text("""
                UPDATE vehiculos SET 
                    modelo = :modelo,
                    ano = :ano,
                    precio = :precio,
                    stock = :stock,
                    marca_id = :marca_id,
                    imagen_url = :imagen_url
                WHERE vin = :vin
            """)
            
            conn.execute(query, {
                "vin": vin,
                "modelo": auto.modelo,
                "ano": auto.ano,
                "precio": auto.precio,
                "stock": auto.stock,
                "marca_id": auto.marca_id,
                "imagen_url": auto.imagen_url
            })
            conn.commit()
            
        return {"mensaje": "Vehículo actualizado correctamente"}
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al actualizar: {str(e)}")

# Obtener un vehículo específico por VIN
@app.get("/vehiculos/{vin}")
def obtener_vehiculo(vin: str):
    try:
        with engine.connect() as conn:
            query = text("""
                SELECT v.vin, v.modelo, v.ano, v.precio, v.stock, v.imagen_url, 
                       m.nombre as marca, v.marca_id, v.categoria_id
                FROM vehiculos v 
                JOIN marcas m ON v.marca_id = m.marca_id
                WHERE v.vin = :vin
            """)
            result = conn.execute(query, {"vin": vin})
            row = result.fetchone()
            
            if not row:
                raise HTTPException(status_code=404, detail="Vehículo no encontrado")
                
            return dict(row._mapping)
            
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error al buscar vehículo: {str(e)}")