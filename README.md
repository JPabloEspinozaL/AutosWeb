# AutosWeb

Aplicación web para la gestión de vehículos, desarrollada con arquitectura separada en frontend, backend y base de datos, utilizando contenedores con Docker.

## Descripción general

AutosWeb es un sistema diseñado para administrar información relacionada con vehículos, permitiendo la consulta, registro y manejo de datos a través de una interfaz web.
El proyecto está dividido en tres módulos principales:

* Frontend: interfaz gráfica del usuario.
* Backend: API y lógica del sistema.
* Database: gestión y estructura de la base de datos.

## Estructura del proyecto

```
AutosWeb/
│── frontend/        # Aplicación web (interfaz)
│── backend/         # API y lógica del servidor
│── database/        # Configuración de la base de datos
└── docker-compose.yml  # Orquestación de servicios con Docker
```

## Ejecución con Docker

Para iniciar todos los servicios del proyecto, ejecutar:

```
docker-compose up --build
```

Esto levanta automáticamente:

* El servidor backend
* El cliente frontend
* La base de datos

## Tecnologías utilizadas

* Docker / Docker Compose
* JavaScript / HTML / CSS
* Node.js / Express
* MySQL / MariaDB

## Autores

Proyecto realizado por:

* pablo
* Israel
* Angel

