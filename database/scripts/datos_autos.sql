-- 1. Aseguramos que existan las CATEGORIAS (Evitamos duplicados)
INSERT INTO categorias (nombre, descripcion) VALUES 
('Superdeportivo', 'Alto rendimiento y velocidad extrema'),
('SUV de Lujo', 'Camionetas premium y espaciosas'),
('Eléctrico', 'Tecnología sustentable de vanguardia'),
('Sedán Ejecutivo', 'Confort y elegancia máxima')
ON CONFLICT DO NOTHING;

-- 2. Aseguramos que existan las MARCAS
INSERT INTO marcas (nombre, pais_origen) VALUES 
('Ferrari', 'Italia'),
('Lamborghini', 'Italia'),
('Tesla', 'USA'),
('Porsche', 'Alemania'),
('McLaren', 'Reino Unido'),
('Rolls-Royce', 'Reino Unido'),
('Bugatti', 'Francia')
ON CONFLICT (nombre) DO NOTHING;

-- 3. Limpiamos autos viejos para no tener basura (Opcional, quita esta línea si quieres conservar lo anterior)
TRUNCATE TABLE vehiculos RESTART IDENTITY;

-- 4. INSERTAMOS LOS AUTOS CON IMÁGENES REALES
-- Nota: Usamos subconsultas (SELECT id FROM...) para obtener el ID correcto de la marca automáticamente

-- FERRARI
INSERT INTO vehiculos (vin, modelo, ano, precio, stock, marca_id, categoria_id, imagen_url) VALUES
('FERRARI-812-GTS', '812 GTS Spider', 2024, 420000.00, 3, (SELECT marca_id FROM marcas WHERE nombre='Ferrari'), 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/ee/Ferrari_812_Superfast_Genf_2018.jpg/1200px-Ferrari_812_Superfast_Genf_2018.jpg'),
('FERRARI-ROMA-01', 'Roma V8 Turbo', 2023, 245000.00, 5, (SELECT marca_id FROM marcas WHERE nombre='Ferrari'), 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/2022_Ferrari_Roma.jpg/1200px-2022_Ferrari_Roma.jpg'),
('FERRARI-SF90-XX', 'SF90 Stradale Hybrid', 2024, 650000.00, 2, (SELECT marca_id FROM marcas WHERE nombre='Ferrari'), 3, 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Ferrari_SF90_Stradale_Hybrid.jpg/1200px-Ferrari_SF90_Stradale_Hybrid.jpg');

-- LAMBORGHINI
INSERT INTO vehiculos (vin, modelo, ano, precio, stock, marca_id, categoria_id, imagen_url) VALUES
('LAMBO-URUS-0001', 'Urus Performante', 2024, 280000.00, 8, (SELECT marca_id FROM marcas WHERE nombre='Lamborghini'), 2, 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Lamborghini_Urus_Performante.jpg/1200px-Lamborghini_Urus_Performante.jpg'),
('LAMBO-HURACAN-X', 'Huracán STO', 2023, 340000.00, 4, (SELECT marca_id FROM marcas WHERE nombre='Lamborghini'), 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/Lamborghini_Huracan_STO.jpg/1200px-Lamborghini_Huracan_STO.jpg'),
('LAMBO-REVUELT-Z', 'Revuelto V12 Hybrid', 2025, 600000.00, 1, (SELECT marca_id FROM marcas WHERE nombre='Lamborghini'), 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/52/Lamborghini_Revuelto_front_view.jpg/1200px-Lamborghini_Revuelto_front_view.jpg');

-- TESLA
INSERT INTO vehiculos (vin, modelo, ano, precio, stock, marca_id, categoria_id, imagen_url) VALUES
('TESLA-PLAID-S01', 'Model S Plaid', 2024, 110000.00, 15, (SELECT marca_id FROM marcas WHERE nombre='Tesla'), 3, 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/14/2018_Tesla_Model_S_75D.jpg/1200px-2018_Tesla_Model_S_75D.jpg'),
('TESLA-X-FALCON1', 'Model X Plaid', 2024, 125000.00, 10, (SELECT marca_id FROM marcas WHERE nombre='Tesla'), 2, 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/92/2017_Tesla_Model_X_100D_Front.jpg/1200px-2017_Tesla_Model_X_100D_Front.jpg');

-- PORSCHE
INSERT INTO vehiculos (vin, modelo, ano, precio, stock, marca_id, categoria_id, imagen_url) VALUES
('PORSCHE-911-GT3', '911 GT3 RS', 2024, 290000.00, 3, (SELECT marca_id FROM marcas WHERE nombre='Porsche'), 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1c/Porsche_992_GT3_RS.jpg/1200px-Porsche_992_GT3_RS.jpg'),
('PORSCHE-TAYCAN1', 'Taycan Turbo S', 2023, 195000.00, 6, (SELECT marca_id FROM marcas WHERE nombre='Porsche'), 3, 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/63/2020_Porsche_Taycan_4S_Cross_Turismo.jpg/1200px-2020_Porsche_Taycan_4S_Cross_Turismo.jpg');

-- MCLAREN
INSERT INTO vehiculos (vin, modelo, ano, precio, stock, marca_id, categoria_id, imagen_url) VALUES
('MCLAREN-720S-01', '720S Spider', 2023, 315000.00, 2, (SELECT marca_id FROM marcas WHERE nombre='McLaren'), 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/22/McLaren_720S_Spider.jpg/1200px-McLaren_720S_Spider.jpg'),
('MCLAREN-ARTURA1', 'Artura Hybrid', 2024, 235000.00, 5, (SELECT marca_id FROM marcas WHERE nombre='McLaren'), 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/McLaren_Artura_Genf_2024.jpg/1200px-McLaren_Artura_Genf_2024.jpg');

-- BUGATTI
INSERT INTO vehiculos (vin, modelo, ano, precio, stock, marca_id, categoria_id, imagen_url) VALUES
('BUGATTI-CHIRON1', 'Chiron Super Sport', 2022, 3500000.00, 1, (SELECT marca_id FROM marcas WHERE nombre='Bugatti'), 1, 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/Bugatti_Chiron_Sport_Genf_2018.jpg/1200px-Bugatti_Chiron_Sport_Genf_2018.jpg');

-- ROLLS ROYCE
INSERT INTO vehiculos (vin, modelo, ano, precio, stock, marca_id, categoria_id, imagen_url) VALUES
('RR-PHANTOM-VIII', 'Phantom VIII', 2024, 550000.00, 2, (SELECT marca_id FROM marcas WHERE nombre='Rolls-Royce'), 4, 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Rolls-Royce_Phantom_VIII_Genf_2018.jpg/1200px-Rolls-Royce_Phantom_VIII_Genf_2018.jpg'),
('RR-CULLINAN-01', 'Cullinan Black Badge', 2024, 450000.00, 4, (SELECT marca_id FROM marcas WHERE nombre='Rolls-Royce'), 2, 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5b/Rolls-Royce_Cullinan_Genf_2018.jpg/1200px-Rolls-Royce_Cullinan_Genf_2018.jpg');