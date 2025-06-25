<?php
session_start();
if (!isset($_SESSION['usuario']) && !isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

include_once 'php/MovimientoController.php';

$movimientoController = new MovimientoController();

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = 10;
$offset = ($pagina - 1) * $limite;

$campoOrden = $_GET['orden'] ?? 'fecha';
$direccion = $_GET['dir'] ?? 'desc';
$busqueda = $_GET['busqueda'] ?? '';
$filtroTipo = $_GET['filtro_tipo'] ?? '';

// Obtener movimientos
$todosLosMovimientos = $movimientoController->obtenerMovimientos();

// Filtro por nombre del producto
if (!empty($busqueda)) {
    $todosLosMovimientos = array_filter($todosLosMovimientos, function ($mov) use ($busqueda) {
        return stripos($mov['producto_nombre'], $busqueda) !== false;
    });
}

// Filtro por tipo
if (!empty($filtroTipo)) {
    $todosLosMovimientos = array_filter($todosLosMovimientos, function ($mov) use ($filtroTipo) {
        return strtolower($mov['tipo']) === strtolower($filtroTipo);
    });
}

// Ordenar
usort($todosLosMovimientos, function ($a, $b) use ($campoOrden, $direccion) {
    $valA = $a[$campoOrden] ?? '';
    $valB = $b[$campoOrden] ?? '';

    if ($campoOrden === 'fecha') {
        $valA = strtotime($valA);
        $valB = strtotime($valB);
    }

    return ($direccion === 'asc') ? $valA <=> $valB : $valB <=> $valA;
});

$totalMovimientos = count($todosLosMovimientos);
$totalPaginas = ceil($totalMovimientos / $limite);
$movimientos = array_slice($todosLosMovimientos, $offset, $limite);

function generarEnlaceOrden($campo, $etiqueta, $actualCampo, $actualDir, $extra = '') {
    $nuevaDir = ($actualCampo === $campo && $actualDir === 'asc') ? 'desc' : 'asc';
    $flecha = ($actualCampo === $campo) ? ($actualDir === 'asc' ? '↑' : '↓') : '';
    return "<a href=\"?orden=$campo&dir=$nuevaDir$extra\">$etiqueta $flecha</a>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Movimientos</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<header class="bg-primary text-white text-center py-4">
    <a href="logout.php" class="btn btn-outline-light position-absolute top-0 end-0 m-3">Cerrar Sesión</a>
    <h1>Consulta de Movimientos</h1>
    <p class="lead">Visualiza y filtra los movimientos registrados.</p>
</header>

<div class="container my-4">

    <div class="row mb-3">
        <div class="col-md-8">
            <form method="GET" class="d-flex">
                <input type="text" name="busqueda" class="form-control me-2" placeholder="Buscar por nombre de producto" value="<?= htmlspecialchars($busqueda) ?>">
                <select name="filtro_tipo" class="form-select me-2">
                    <option value="">Todos los tipos</option>
                    <option value="Entrada" <?= $filtroTipo === 'Entrada' ? 'selected' : '' ?>>Entrada</option>
                    <option value="Salida" <?= $filtroTipo === 'Salida' ? 'selected' : '' ?>>Salida</option>
                    <option value="Traslado" <?= $filtroTipo === 'Traslado' ? 'selected' : '' ?>>Traslado</option>
                    <option value="Ajuste" <?= $filtroTipo === 'Ajuste' ? 'selected' : '' ?>>Ajuste</option>
                </select>
                <button type="submit" class="btn btn-outline-primary">Filtrar</button>
            </form>
        </div>
        <div class="col-md-4 text-end">
            <a href="dashboard.php" class="btn btn-primary">Volver al Inicio</a>
        </div>
    </div>

    <?php if (!empty($movimientos)): ?>
    <div class="table-responsive">
    <table class="table table-bordered table-hover text-center">
        <thead class="table-light">
            <tr>
                <th>Producto</th>
                <th><?= generarEnlaceOrden('tipo', 'Tipo', $campoOrden, $direccion, "&busqueda=$busqueda&filtro_tipo=$filtroTipo") ?></th>
                <th>Cantidad</th>
                <th><?= generarEnlaceOrden('estado_producto', 'Estado', $campoOrden, $direccion, "&busqueda=$busqueda&filtro_tipo=$filtroTipo") ?></th>
                <th>Ubicación Anterior</th>
                <th>Ubicación Nueva</th>
                <th>Responsable</th>
                <th><?= generarEnlaceOrden('fecha', 'Fecha', $campoOrden, $direccion, "&busqueda=$busqueda&filtro_tipo=$filtroTipo") ?></th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($movimientos as $mov): ?>
            <tr>
                <td><?= htmlspecialchars($mov['producto_nombre']) ?> (<?= htmlspecialchars($mov['numero_inventario']) ?>)</td>
                <td><?= ucfirst($mov['tipo']) ?></td>
                <td><?= $mov['cantidad'] ?></td>
                <td><?= htmlspecialchars($mov['estado_producto']) ?></td>
                <td><?= htmlspecialchars($mov['ubicacion_anterior']) ?></td>
                <td><?= htmlspecialchars($mov['ubicacion_nueva']) ?></td>
                <td><?= htmlspecialchars($mov['responsable']) ?></td>
                <td><?= htmlspecialchars($mov['fecha']) ?></td>
                <td><?= htmlspecialchars($mov['observaciones']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>

    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>&orden=<?= $campoOrden ?>&dir=<?= $direccion ?>&busqueda=<?= urlencode($busqueda) ?>&filtro_tipo=<?= urlencode($filtroTipo) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

    <?php else: ?>
    <div class="alert alert-warning text-center">
        No hay movimientos que coincidan con la búsqueda o filtros.
    </div>
    <?php endif; ?>

</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
    <p>&copy; 2025 Fiscalía Regional del Maule | Todos los derechos reservados</p>
</footer>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
