# 🗂️ Sistema de Inventario - Fiscalía Regional del Maule

Este sistema permite gestionar el inventario de activos e insumos del área informática. Se puede registrar y consultar movimientos como entradas, salidas, traslados y ajustes, manteniendo un control de la ubicación y estado de los productos.

---

## 🚀 Funcionalidades principales

- ✅ Autenticación con LDAP (Active Directory)
- ✅ Registro de productos (activos e insumos)
- ✅ Registro de movimientos (Entrada, Salida, Traslado, Ajuste)
- ✅ Control de ubicación actual de productos
- ✅ Bloqueo de movimientos si el producto no está en "Bodega UGI"
- ✅ Paginación, ordenamiento y búsqueda de movimientos
- ✅ Panel principal con acceso a todas las opciones (Dashboard)
- ✅ Sistema de sesiones con botón de cierre de sesión

---

## 🛠 Tecnologías utilizadas

- PHP 8.2
- MySQL / MariaDB
- Bootstrap 5
- JavaScript
- XAMPP para desarrollo local

---

## 📦 Requisitos

- PHP >= 8.0
- MySQL o MariaDB
- Servidor Apache (recomendado: XAMPP o similar)
- Acceso a un servidor LDAP (Active Directory)

---

## ⚙️ Configuración del proyecto

1. **Clonar el repositorio:**

   ```bash
   git clone https://github.com/juansalinasaedo/inventario_prueba.git

2. **Copiar y configurar el archivo de entorno:**
cp .env.example .env

3. **Editar el archivo .env con tus datos de conexión:**
DB_HOST=localhost
DB_NAME=inventario
DB_USER=root
DB_PASS=

LDAP_SERVER=ldap://dominio.local
LDAP_PORT=389
LDAP_DOMAIN=DOMINIO
LDAP_BASE_DN=DC=dominio,DC=local

4. **Importar la base de datos:**
Importa el archivo inventario.sql (si existe) en tu gestor de base de datos (como phpMyAdmin).

5. **Iniciar servidor local:**
Abre XAMPP

Inicia Apache y MySQL

Accede al proyecto desde http://localhost/inventario_prueba/

🧑‍💻 Estructura del proyecto

bash
Copiar
Editar
inventario_prueba/
├── css/
├── js/
├── php/
│   ├── db.php
│   ├── ProductoController.php
│   ├── MovimientoController.php
│   └── obtener_ubicacion.php
├── registroActivo.php
├── registroMovimiento.php
├── consultaMovimientos.php
├── dashboard.php
├── validar_login.php
├── logout.php
└── .env

✍️ Autor
Juan Salinas Aedo
Desarrollado para la Fiscalía Regional del Maule

📄 Licencia
Uso interno. No distribuible públicamente sin autorización.