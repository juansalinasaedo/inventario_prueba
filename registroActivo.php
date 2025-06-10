<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Activo</title>
    <!-- Enlace a Bootstrap local -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Enlace a Font Awesome para los iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        /* Efecto al pasar el cursor sobre los botones */
        .btn:hover {
            transform: scale(1.1);
            transition: transform 0.3s ease;
        }

        /* Animación en el botón para volver al home */
        .btn-home {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease-in-out;
        }

        .btn-home::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background-color: rgba(0, 123, 255, 0.2); /* Color de fondo cuando se activa */
            transition: all 0.5s ease-in-out;
            transform: translate(-50%, -50%) scale(0);
            border-radius: 50%;
        }

        .btn-home:hover::before {
            transform: translate(-50%, -50%) scale(1);
        }
    </style>
</head>

<body>
    <!-- Cabecera -->
    <header class="bg-primary text-white text-center py-4">
        <h1>Registrar Nuevo Activo</h1>
        <p class="lead">Ingresa la información del activo que deseas registrar.</p>
    </header>

    <!-- Cuerpo -->
    <div class="container my-5">
        <form method="POST" action="registroActivo.php">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Activo:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="numero_inventario" class="form-label">Número de Inventario:</label>
                <input type="text" name="numero_inventario" id="numero_inventario" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="numero_serie" class="form-label">Número de Serie:</label>
                <input type="text" name="numero_serie" id="numero_serie" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado:</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="nuevo">Nuevo</option>
                    <option value="bueno">Bueno</option>
                    <option value="con observaciones">Con Observaciones</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="fecha_adquisicion" class="form-label">Fecha de Adquisición:</label>
                <input type="date" name="fecha_adquisicion" id="fecha_adquisicion" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div class="mb-3">
                <label for="ubicacion_actual" class="form-label">Ubicación Actual:</label>
                <input type="text" name="ubicacion_actual" id="ubicacion_actual" class="form-control" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Registrar
                </button>
            </div>
        </form>

        <!-- Botón para volver al home -->
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-home btn-outline-primary">
                <i class="fas fa-home"></i> Volver al Home
            </a>
        </div>
    </div>

    <!-- Pie de página (Footer) -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Fiscalía Regional del Maule | Todos los derechos reservados</p>
    </footer>

    <!-- Incluir el script de Bootstrap local -->
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
