# Restaurante Western

Aplicación web para un restaurante de temática western, desarrollada con **PHP** y **MariaDB** siguiendo el patrón **MVC** y desplegada con **Docker**.

## Tecnologías

- PHP + Apache
- MariaDB
- HTML, CSS y JavaScript
- Composer
- Docker y Docker Compose

## Estructura

```
├── controllers/            # Lógica de la aplicación
├── models/                 # Acceso a datos
├── views/                  # Vistas
├── js/ y styles/           # Scripts y estilos
├── imgs/ y uploads/        # Imágenes
├── docs/                   # Documentación
├── index.php               # Punto de entrada
├── restaurante_western.sql # Base de datos
├── Dockerfile
└── docker-compose.yml
```

## Cómo ejecutarlo con Docker

Necesitas tener [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado.

1. Clona el repositorio:
```bash
   git clone https://github.com/JaviCastro03/App_Restaurante_Western.git
   cd App_Restaurante_Western
```
2. Arranca los contenedores:
```bash
   docker compose up -d --build
```
3. Abre en el navegador: **http://localhost:8888**

La base de datos `restaurante_western` se crea y se rellena sola la primera vez, a partir del archivo `restaurante_western.sql`.

| Servicio | Contenedor | Puerto |
|----------|------------|--------|
| Aplicación web | `web` | 8888 |
| Base de datos (MariaDB) | `bbdd` | 3307 |

### Comandos útiles

```bash
docker compose down        # Parar los contenedores
docker compose down -v     # Parar y borrar la base de datos (se reimporta al volver a arrancar)
```

## Autor

Desarrollado por [Javi](https://github.com/JaviCastro03).
