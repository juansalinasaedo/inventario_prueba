<?php
include_once 'php/MovimientoController.php';
include_once 'php/ProductoController.php';

$movimientoController = new MovimientoController();
$productoController = new ProductoController();

$productos = $productoController->obtenerProductosParaFormulario();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Movimiento</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header class="bg-primary text-white text-center py-4">
    <h1>Registrar Movimiento</h1>
    <p class="lead">Formulario para registrar entrada, salida o traslado de productos.</p>
</header>

<div class="container my-5">
    <form action="guardarMovimiento.php" method="POST">
        <div class="mb-3">
            <label for="producto_id" class="form-label">Producto</label>
            <select class="form-select" name="producto_id" required>
                <option value="">Seleccione un producto</option>
                <?php foreach ($productos as $producto): ?>
                    <option value="<?= $producto['id'] ?>"><?= $producto['nombre'] ?> - <?= $producto['numero_inventario'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de Movimiento</label>
            <select class="form-select" name="tipo" required>
                <option value="">Seleccione tipo</option>
                <option value="Entrada">Entrada</option>
                <option value="Salida">Salida</option>
                <option value="Traslado">Traslado</option>
                <option value="Ajuste">Ajuste</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="motivo" class="form-label">Motivo</label>
            <textarea class="form-control" name="motivo" required></textarea>
        </div>

        <div class="mb-3">
            <label for="responsable" class="form-label">Responsable</label>
            <input type="text" class="form-control" name="responsable" placeholder="Nombre del responsable" required>
        </div>

        <div class="mb-3">
            <label for="fecha_movimiento" class="form-label">Fecha de Movimiento</label>
            <input type="date" class="form-control" name="fecha_movimiento" required>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Guardar Movimiento</button>
            <a href="consultaMovimientos.php" class="btn btn-secondary">Cancelar</a>
            <a href="index.php" class="btn btn-outline-dark">Volver al Inicio</a>
        </div>
    </form>
</div>

<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 Fiscalía Regional del Maule | Todos los derechos reservados</p>
</footer>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
