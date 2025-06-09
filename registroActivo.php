<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Activo</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJv3fUs0hL2jG9NN15hHmwqzF5fZ2XlDk2jt4jj2vYJ9BhHboHXyl56yN7kS" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <header class="text-center mb-4">
            <h1>Registrar Nuevo Activo</h1>
        </header>

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
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </form>
    </div>

    <!-- Incluir Bootstrap JS y Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
