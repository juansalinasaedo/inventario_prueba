<?php
include_once 'php/sesion.php';
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
    <style>.hidden { display: none; }</style>
</head>
<body>
<header class="bg-primary text-white text-center py-4 position-relative">
    <a href="logout.php" class="btn btn-outline-light position-absolute top-0 end-0 m-3">Cerrar Sesión</a>
    <h1>Registrar Movimiento</h1>
    <p class="lead">Formulario para registrar entrada, salida, traslado o ajuste de productos.</p>
</header>

<div class="container my-5">
    <?php if (isset($_SESSION['errores_movimiento'])): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($_SESSION['errores_movimiento'] as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errores_movimiento']); ?>
    <?php endif; ?>

    <form action="guardarMovimiento.php" method="POST" id="formMovimiento">
        <div class="mb-3">
            <label for="producto_id" class="form-label">Producto</label>
            <select class="form-select" name="producto_id" id="producto_id" required>
                <option value="">Seleccione un producto</option>
                <?php foreach ($productos as $producto): ?>
                    <?php if (isset($producto['id'], $producto['nombre'], $producto['numero_inventario'])): ?>
                        <option value="<?= htmlspecialchars($producto['id']) ?>">
                            <?= htmlspecialchars($producto['nombre']) ?> - <?= htmlspecialchars($producto['numero_inventario']) ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de Movimiento</label>
            <select class="form-select" name="tipo" id="tipo" required>
                <option value="">Seleccione tipo</option>
                <option value="Entrada">Entrada</option>
                <option value="Salida">Salida</option>
                <option value="Traslado">Traslado</option>
                <option value="Ajuste">Ajuste</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="ubicacion_actual" class="form-label">Ubicación Actual</label>
            <input type="text" class="form-control" id="ubicacion_actual" name="ubicacion_actual">
            <input type="hidden" name="ubicacion_anterior" id="ubicacion_anterior">
        </div>

        <div class="mb-3 hidden" id="ubicacionNuevaContainer">
            <label for="ubicacion_nueva" class="form-label">Nueva Ubicación</label>
            <input type="text" class="form-control" name="ubicacion_nueva" id="ubicacion_nueva" placeholder="Ej. Oficina Jurídica">
        </div>

        <div class="mb-3">
            <label for="motivo" class="form-label">Motivo</label>
            <textarea class="form-control" name="motivo" required></textarea>
        </div>

        <div class="mb-3">
            <label for="responsable" class="form-label">Responsable</label>
            <input type="text" class="form-control" name="responsable" required>
        </div>

        <div class="mb-3">
            <label for="fecha_movimiento" class="form-label">Fecha de Movimiento</label>
            <input type="date" class="form-control" name="fecha_movimiento" required>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Guardar Movimiento</button>
            <a href="consultaMovimientos.php" class="btn btn-secondary">Cancelar</a>
            <a href="dashboard.php" class="btn btn-outline-dark">Volver al Inicio</a>
        </div>
    </form>
</div>

<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 Fiscalía Regional del Maule | Todos los derechos reservados</p>
</footer>

<script>
    const tipoSelect = document.getElementById('tipo');
    const ubicacionContainer = document.getElementById('ubicacionNuevaContainer');
    const ubicacionInput = document.getElementById('ubicacion_nueva');
    const productoSelect = document.getElementById('producto_id');
    const ubicacionActualInput = document.getElementById('ubicacion_actual');

    tipoSelect.addEventListener('change', function () {
        if (this.value === 'Traslado' || this.value === 'Ajuste') {
            ubicacionContainer.classList.remove('hidden');
            ubicacionInput.required = true;
        } else {
            ubicacionContainer.classList.add('hidden');
            ubicacionInput.required = false;
            ubicacionInput.value = '';
        }
    });

    productoSelect.addEventListener('change', function () {
        const productoId = this.value;
        if (productoId) {
            fetch(`php/obtener_ubicacion.php?id=${productoId}`)
                .then(response => response.json())
                .then(data => {
                    ubicacionActualInput.value = data.ubicacion_actual || 'No registrada';
                    document.getElementById('ubicacion_anterior').value = data.ubicacion_actual || '';
                })
                .catch(error => {
                    console.error('Error obteniendo ubicación:', error);
                    ubicacionActualInput.value = '';
                });
        } else {
            ubicacionActualInput.value = '';
        }
    });
</script>
</body>
</html>