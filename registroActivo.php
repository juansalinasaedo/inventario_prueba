<?php
include_once 'php/sesion.php';

$mensaje_exito = '';
$mensaje_error = '';

$nombre = '';
$numero_inventario = '';
$numero_serie = '';
$estado = 'nuevo';
$fecha_adquisicion = date('Y-m-d');
$ubicacion_actual = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include_once 'php/ProductoController.php';

    $nombre = $_POST['nombre'] ?? '';
    $numero_inventario = $_POST['numero_inventario'] ?? '';
    $numero_serie = $_POST['numero_serie'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $fecha_adquisicion = $_POST['fecha_adquisicion'] ?? '';
    $ubicacion_actual = $_POST['ubicacion_actual'] ?? '';

    $productoController = new ProductoController();
    $resultado = $productoController->registrarProducto(
        $nombre,
        $numero_inventario,
        $numero_serie,
        $estado,
        $fecha_adquisicion,
        $ubicacion_actual
    );

    if ($resultado) {
        $mensaje_exito = "El producto fue registrado exitosamente.";

        // Limpiar campos
        $nombre = '';
        $numero_inventario = '';
        $numero_serie = '';
        $estado = 'nuevo';
        $fecha_adquisicion = date('Y-m-d');
        $ubicacion_actual = '';
    } else {
        $mensaje_error = "Ocurrió un error al registrar el producto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Activo</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .btn:hover {
            transform: scale(1.1);
            transition: transform 0.3s ease;
        }
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
            background-color: rgba(0, 123, 255, 0.2);
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
    <header class="bg-primary text-white text-center py-4 position-relative">
        <a href="logout.php" class="btn btn-outline-light position-absolute top-0 end-0 m-3">Cerrar Sesión</a>
        <h1>Registrar Nuevo Activo</h1>
        <p class="lead">Ingresa la información del activo que deseas registrar.</p>
    </header>

    <div class="container my-5">

        <?php if ($mensaje_exito): ?>
            <div class="alert alert-success text-center">
                <?= $mensaje_exito ?>
            </div>
        <?php endif; ?>

        <?php if ($mensaje_error): ?>
            <div class="alert alert-danger text-center">
                <?= $mensaje_error ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="registroActivo.php">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Activo:</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required
                       value="<?= htmlspecialchars($nombre) ?>">
            </div>

            <div class="mb-3">
                <label for="numero_inventario" class="form-label">Número de Inventario:</label>
                <input type="text" name="numero_inventario" id="numero_inventario" class="form-control" required
                       value="<?= htmlspecialchars($numero_inventario) ?>">
            </div>

            <div class="mb-3">
                <label for="numero_serie" class="form-label">Número de Serie:</label>
                <input type="text" name="numero_serie" id="numero_serie" class="form-control" required
                       value="<?= htmlspecialchars($numero_serie) ?>">
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado:</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="nuevo" <?= $estado === 'nuevo' ? 'selected' : '' ?>>Nuevo</option>
                    <option value="bueno" <?= $estado === 'bueno' ? 'selected' : '' ?>>Bueno</option>
                    <option value="con observaciones" <?= $estado === 'con observaciones' ? 'selected' : '' ?>>Con Observaciones</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="fecha_adquisicion" class="form-label">Fecha de Adquisición:</label>
                <input type="date" name="fecha_adquisicion" id="fecha_adquisicion" class="form-control" required
                       value="<?= htmlspecialchars($fecha_adquisicion) ?>">
            </div>

            <div class="mb-3">
                <label for="ubicacion_actual" class="form-label">Ubicación Actual:</label>
                <input type="text" name="ubicacion_actual" id="ubicacion_actual" class="form-control" required
                       value="<?= htmlspecialchars($ubicacion_actual) ?>">
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Registrar
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-home btn-outline-primary">
                <i class="fas fa-home"></i> Volver al Home
            </a>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Fiscalía Regional del Maule | Todos los derechos reservados</p>
    </footer>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>