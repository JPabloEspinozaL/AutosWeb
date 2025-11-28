-- Tablas requeridas por el PDF (Pág. 23)
CREATE TABLE marcas (
    marca_id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    pais_origen VARCHAR(100)
);

CREATE TABLE categorias (
    categoria_id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT
);

CREATE TABLE vehiculos (
    vehiculo_id SERIAL PRIMARY KEY,
    vin VARCHAR(17) NOT NULL UNIQUE,
    modelo VARCHAR(255) NOT NULL,
    ano INT NOT NULL,
    precio DECIMAL(12,2) NOT NULL,
    stock INT NOT NULL CHECK (stock >= 0),
    marca_id INT REFERENCES marcas(marca_id),
    categoria_id INT REFERENCES categorias(categoria_id),
    imagen_url VARCHAR(255),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE
);

-- DATOS DE PRUEBA (Para que no esté vacío al iniciar)
INSERT INTO marcas (nombre, pais_origen) VALUES ('Ferrari', 'Italia'), ('Lamborghini', 'Italia'), ('Tesla', 'USA');
INSERT INTO categorias (nombre, descripcion) VALUES ('Superdeportivo', 'Alto rendimiento'), ('SUV Lujo', 'Camioneta premium');