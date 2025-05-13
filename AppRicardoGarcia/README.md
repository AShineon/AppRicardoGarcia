# AppRicardoGarcia
# ecommerce-app/
# │
# ├── index.php                     # Punto de entrada principal (dashboard o login)
# ├── .htaccess                     # Reglas de redirección (opcional si usas Apache)
# │
# ├── assets/                       # Recursos estáticos (CSS, JS, imágenes)
# │   ├── css/
# │   ├── js/
# │   └── img/
# │
# ├── config/                       # Configuración general y conexión DB
# │   ├── database.php              # Conexión SQLite
# │   └── config.php                # Constantes globales, rutas base
# │
# ├── controllers/                 # Lógica de negocio por módulo
# │   ├── ProductosController.php
# │   ├── VentasController.php
# │   ├── ClientesController.php
# │   └── ...
# │
# ├── models/                      # Lógica de acceso a datos
# │   ├── Producto.php
# │   ├── Venta.php
# │   ├── Cliente.php
# │   └── ...
# │
# ├── views/                       # Plantillas HTML/PHP separadas por módulos
# │   ├── layouts/                 # Header, footer, menú lateral
# │   │   ├── header.php
# │   │   ├── sidebar.php
# │   │   └── footer.php
# │   ├── dashboard/               # Página de inicio
# │   │   └── index.php
# │   ├── productos/
# │   │   ├── index.php            # Listado
# │   │   ├── crear.php
# │   │   └── editar.php
# │   ├── ventas/
# │   ├── clientes/
# │   ├── proveedores/
# │   └── reportes/
# │
# ├── routes/                      # Manejador de rutas (puedes usar switch o un mini router)
# │   └── web.php
# │
# ├── public/                      # Punto de entrada si usas servidor local
# │   └── index.php                # Redirige a ../index.php
# │
# ├── storage/                     # Base de datos SQLite, backups, logs
# │   ├── basedatos.db
# │   └── logs/
# │
# └── helpers/                     # Funciones reutilizables (validaciones, formateo, etc.)
#     └── utils.php
# 
# 
# index.php: el archivo que carga por defecto, y desde el que puedes redirigir a otras vistas por ejemplo esl login.
# 
# controllers/: archivos PHP donde controlas qué vista cargar, cómo manejar formularios, etc.
# 
# models/: clases o scripts que consultan/actualizan la base de datos.
# 
# views/: archivos HTML/PHP donde va la interfaz del usuario.
# 
# config/: conexión a SQLite y configuraciones globales.
# 
# storage/: donde se guarda el archivo de base de datos .db y logs si decides registrarlos.
# 
