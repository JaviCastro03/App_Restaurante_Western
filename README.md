# Restaurante Western

Aplicación web para la gestión de un restaurante de temática western, desarrollada con **PHP** y **MySQL** siguiendo el patrón **MVC**. Permite mostrar la carta, gestionar los platos y administrar el restaurante desde un panel privado, todo con un diseño inspirado en el Lejano Oeste.

## Características

- Carta de platos organizada por categorías
- Registro e inicio de sesión de usuarios (con cierre de sesión)
- Panel de administración para crear, editar y eliminar platos
- Subida de imágenes de los platos
- Base de datos incluida y lista para importar
- Entorno de desarrollo reproducible con **Docker**

## Tecnologías

- PHP
- MySQL
- HTML, CSS y JavaScript
- Composer (gestión de dependencias)
- Docker y Docker Compose
- Xdebug (depuración)
- Apache con `.htaccess` para la configuración de rutas

## Estructura del proyecto

```
restaurant_western/
├── controllers/            # Lógica de la aplicación
├── models/                 # Acceso a datos y entidades
├── views/                  # Vistas e interfaz de usuario
├── js/                     # Scripts del cliente
├── styles/                 # Hojas de estilo
├── imgs/                   # Imágenes del sitio
├── uploads/                # Archivos subidos por los usuarios
├── docs/                   # Documentación del proyecto
├── index.php               # Punto de entrada
├── logout.php              # Cierre de sesión
├── restaurante_western.sql # Script de la base de datos
├── composer.json           # Dependencias de PHP
├── Dockerfile              # Imagen de la aplicación
├── docker-compose.yml      # Servicios (app y base de datos)
└── xdebug.ini              # Configuración de depuración
```

## Instalación

### Opción 1: con Docker

1. Clona el repositorio:
```bash
   git clone https://github.com/JaviCastro03/App_Restaurante_Western.git
   cd App_Restaurante_Western
```
2. Levanta los contenedores:
```bash
   docker compose up -d --build
```
3. Importa `restaurante_western.sql` en la base de datos si no se carga automáticamente.
4. Abre la aplicación en el navegador (por defecto `http://localhost`, o el puerto definido en `docker-compose.yml`).

### Opción 2: con XAMPP

1. Clona el repositorio dentro de `C:\xampp\htdocs\`.
2. Arranca **Apache** y **MySQL** desde el panel de XAMPP.
3. Entra en phpMyAdmin, crea la base de datos `restaurante_western` e importa el archivo `restaurante_western.sql`.
4. Instala las dependencias:
```bash
   composer install
```
5. Configura las credenciales de la base de datos en el archivo de conexión de `models/`.
6. Abre `http://localhost/App_Restaurante_Western` en el navegador.

## Configuración

Las credenciales de la base de datos no deben subirse al repositorio. Usa un archivo de configuración local y mantenlo en el `.gitignore`.

## Autor

Desarrollado por [Javi](https://github.com/JaviCastro03).
