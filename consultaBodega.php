<?php
include_once 'php/sesion.php';
include('php/productoController.php');

$productoController = new ProductoController();

$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'nombre';
$orderDirection = isset($_GET['orderDirection']) ? $_GET['orderDirection'] : 'ASC';
$bodegaFilter = isset($_GET['bodega']) ? $_GET['bodega'] : '';
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = 10;
$offset = ($pagina - 1) * $limite;

$bodegas = $productoController->obtenerBodegas();
$totalProductos = count($productoController->obtenerProductos($orderBy, $orderDirection, $bodegaFilter, 10000, 0));
$totalPaginas = ceil($totalProductos / $limite);
$productos = $productoController->obtenerProductos($orderBy, $orderDirection, $bodegaFilter, $limite, $offset);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Bodega</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body>
<header class="bg-primary text-white text-center py-4 position-relative">
    <a href="logout.php" class="btn btn-outline-light position-absolute top-0 end-0 m-3">Cerrar Sesión</a>
    <h1>Consulta de Bodega</h1>
    <p class="lead">Visualiza todos los productos registrados en la bodega.</p>
</header>

<div class="container my-5">
    <?php if (!empty($productos)): ?>
    <div class="row mb-4 text-center">
        <div class="col-md-12">
            <form method="POST" action="dashboard.php" style="display:inline-block;">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-arrow-left"></i> Volver al Inicio
                </button>
            </form>
            <form method="GET" action="exportarExcel.php" style="display:inline-block; margin-left: 10px;">
                <input type="hidden" name="orderBy" value="<?= $orderBy ?>">
                <input type="hidden" name="orderDirection" value="<?= $orderDirection ?>">
                <input type="hidden" name="bodega" value="<?= $bodegaFilter ?>">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Exportar a Excel
                </button>
            </form>
            <form method="GET" action="exportarPDF.php" style="display:inline-block; margin-left: 10px;">
                <input type="hidden" name="orderBy" value="<?= $orderBy ?>">
                <input type="hidden" name="orderDirection" value="<?= $orderDirection ?>">
                <input type="hidden" name="bodega" value="<?= $bodegaFilter ?>">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Exportar a PDF
                </button>
            </form>
        </div>
    </div>

    <form method="GET" action="consultaBodega.php" class="row mb-4">
        <div class="col-md-4">
            <select name="orderBy" class="form-control" onchange="this.form.submit()">
                <option value="nombre" <?= $orderBy == 'nombre' ? 'selected' : '' ?>>Orden Alfabético</option>
                <option value="numero_inventario" <?= $orderBy == 'numero_inventario' ? 'selected' : '' ?>>Por N° de Inventario</option>
                <option value="numero_serie" <?= $orderBy == 'numero_serie' ? 'selected' : '' ?>>Por N° de Serie</option>
                <option value="estado" <?= $orderBy == 'estado' ? 'selected' : '' ?>>Por Estado</option>
                <option value="ubicacion_actual" <?= $orderBy == 'ubicacion_actual' ? 'selected' : '' ?>>Por Ubicación</option>
                <option value="fecha_adquisicion" <?= $orderBy == 'fecha_adquisicion' ? 'selected' : '' ?>>Por Fecha de Adquisición</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="orderDirection" class="form-control" onchange="this.form.submit()">
                <option value="ASC" <?= $orderDirection == 'ASC' ? 'selected' : '' ?>>Ascendente</option>
                <option value="DESC" <?= $orderDirection == 'DESC' ? 'selected' : '' ?>>Descendente</option>
            </select>
        </div>
        <div class="col-md-5">
            <select name="bodega" class="form-control" onchange="this.form.submit()">
                <option value="">Selecciona una Bodega</option>
                <?php foreach ($bodegas as $bodega): ?>
                    <option value="<?= $bodega['nombre'] ?>" <?= $bodegaFilter == $bodega['nombre'] ? 'selected' : '' ?>>
                        <?= $bodega['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>Nombre</th>
                <th>N° Inventario</th>
                <th>N° Serie</th>
                <th>Estado</th>
                <th>Ubicación</th>
                <th>Fecha de Adquisición</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?= $producto['nombre'] ?></td>
                <td><?= $producto['numero_inventario'] ?></td>
                <td><?= $producto['numero_serie'] ?></td>
                <td><?= $producto['estado'] ?></td>
                <td><?= $producto['ubicacion_actual'] ?></td>
                <td><?= $producto['fecha_adquisicion'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>&orderBy=<?= $orderBy ?>&orderDirection=<?= $orderDirection ?>&bodega=<?= $bodegaFilter ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

    <?php else: ?>
    <div class="alert alert-warning">
        No hay productos registrados en la bodega.
    </div>
    <?php endif; ?>
</div>

<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 Fiscalía Regional del Maule | Todos los derechos reservados</p>
</footer>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
