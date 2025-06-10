# 🗂️ Sistema de Inventario - Fiscalía Regional del Maule

Sistema web para gestionar el inventario de productos informáticos de la Fiscalía Regional del Maule. Permite registrar, consultar, filtrar, ordenar y exportar información de la bodega de activos.

---

## 🚀 Funcionalidades

- Registro y visualización de productos informáticos
- Filtro por bodega
- Ordenamiento ascendente/descendente por:
  - Nombre
  - Número de inventario
  - Serie
  - Estado
  - Ubicación actual
  - Fecha de adquisición
- Exportación a:
  - 📊 Excel
  - 📄 PDF
- Paginación de resultados
- Interfaz responsive con Bootstrap 5

---

## 🛠️ Requisitos

- PHP 8.x
- MySQL / MariaDB
- Composer
- Servidor local (XAMPP, WAMP, Laragon, etc.)

---

## 📦 Instalación

### 1. Clona el repositorio

```bash
git clone https://github.com/juansalinasaedo/inventario_prueba.git
cd inventario_prueba
```

### 2. Instala dependencias

```bash
composer install
```

### 3. Importa la base de datos

Usa phpMyAdmin o la terminal para importar el archivo:

`inventario_oficina.sql`

### 4. Configura el entorno

Copia el archivo de ejemplo `.env.example` y crea tu archivo `.env` con tus credenciales:

```bash
cp .env.example .env
```

Edita `.env` con tu configuración local:

```env
DB_HOST=localhost
DB_NAME=inventario_oficina
DB_USER=root
DB_PASS=
```

---

## ▶️ Uso del sistema

1. Inicia Apache y MySQL desde tu entorno local (XAMPP, etc.).

2. Abre el navegador y visita:

```
http://localhost/inventario_fiscalia/index.php
```

---

## 📤 Exportaciones disponibles

| Formato | Archivo              | Descripción                                  |
|---------|----------------------|----------------------------------------------|
| Excel   | `exportarExcel.php`  | Exporta los productos filtrados a tabla Excel |
| PDF     | `exportarPDF.php`    | Genera un informe con logo, tabla y fecha     |

---

## 🔐 Seguridad aplicada

- Consultas preparadas con `PDO` (prevención de SQL Injection)
- Credenciales gestionadas con archivo `.env`
- Escapado de salida HTML para prevenir XSS
- Validación de filtros y columnas de ordenamiento
- `.gitignore` para evitar subir archivos sensibles

---

## 📁 Estructura recomendada

```
inventario_fiscalia/
├── php/
│   ├── db.php
│   └── productoController.php
├── css/
├── js/
├── img/
├── vendor/
├── consultaBodega.php
├── exportarExcel.php
├── exportarPDF.php
├── registroActivo.php
├── index.php
├── inventario_oficina.sql
├── .env.example
└── README.md
```

---

## 👤 Autor

**Juan Salinas**  
Ingeniero Informático – Fiscalía Regional del Maule

---

## ✅ Licencia

Este proyecto es de uso interno. No cuenta con licencia pública.
