<?php
include_once 'php/MovimientoController.php';

$movimientoController = new MovimientoController();

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = 10;
$offset = ($pagina - 1) * $limite;

$todosLosMovimientos = $movimientoController->obtenerMovimientos();
$totalMovimientos = count($todosLosMovimientos);
$totalPaginas = ceil($totalMovimientos / $limite);
$movimientos = array_slice($todosLosMovimientos, $offset, $limite);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Movimientos</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body>

<header class="bg-primary text-white text-center py-4">
    <h1>Consulta de Movimientos</h1>
    <p class="lead">Visualiza todos los movimientos registrados en el sistema.</p>
</header>

<div class="container my-5">
    <div class="row mb-4 text-center">
        <div class="col-md-12">
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Volver al Inicio
            </a>
        </div>
    </div>

    <?php if (!empty($movimientos)): ?>

    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Estado</th>
                <th>Ubicación Anterior</th>
                <th>Ubicación Nueva</th>
                <th>Responsable</th>
                <th>Fecha</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movimientos as $mov): ?>
            <tr>
                <td><?= htmlspecialchars($mov['producto_nombre']) ?> (<?= htmlspecialchars($mov['numero_inventario']) ?>)</td>
                <td><?= ucfirst($mov['tipo']) ?></td>
                <td><?= $mov['cantidad'] ?></td>
                <td><?= $mov['estado_producto'] ?></td>
                <td><?= $mov['ubicacion_anterior'] ?></td>
                <td><?= $mov['ubicacion_nueva'] ?></td>
                <td><?= $mov['responsable_nombre'] ?></td>
                <td><?= $mov['fecha'] ?></td>
                <td><?= $mov['observaciones'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

    <?php else: ?>
    <div class="alert alert-warning text-center">
        No hay movimientos registrados en el sistema.
    </div>
    <?php endif; ?>
</div>

<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 Fiscalía Regional del Maule | Todos los derechos reservados</p>
</footer>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
