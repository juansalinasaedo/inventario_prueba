<?php
// Incluir el archivo de productoController.php para utilizar la clase ProductoController
include('php/productoController.php');

// Crear una instancia de ProductoController
$productoController = new ProductoController();

// Obtener los parámetros de orden (por defecto se ordena por nombre ascendente)
$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'nombre';
$orderDirection = isset($_GET['orderDirection']) ? $_GET['orderDirection'] : 'ASC';
$bodegaFilter = isset($_GET['bodega']) ? $_GET['bodega'] : '';

// Cambiar la dirección del orden cuando se haga clic en el mismo encabezado
if ($orderDirection == 'ASC') {
    $orderDirection = 'DESC';
} else {
    $orderDirection = 'ASC';
}

// Obtener los productos ordenados y filtrados
$productos = $productoController->obtenerProductos($orderBy, $orderDirection, $bodegaFilter);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Bodega</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .card:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }

        /* Estilo para el botón "Volver" */
        .btn-back {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease-in-out;
        }

        .btn-back::before {
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

        .btn-back:hover::before {
            transform: translate(-50%, -50%) scale(1);
        }

        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <!-- Cabecera -->
    <header class="bg-primary text-white text-center py-4">
        <h1>Consulta de Bodega</h1>
        <p class="lead">Visualiza todos los productos registrados en la bodega.</p>
    </header>

    <!-- Cuerpo -->
    <div class="container my-5">
        <!-- Verificar si hay productos disponibles -->
        <?php if (!empty($productos)): ?>
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <form method="POST" action="index.php">
                    <button class="btn btn-primary btn-back" type="submit">
                        <i class="fas fa-arrow-left"></i> Volver al Inicio
                    </button>
                </form>
            </div>
        </div>

        <!-- Filtro de Ordenación y Búsqueda -->
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <form method="GET" action="consultaBodega.php">
                    <div class="input-group">
                        <select name="orderBy" class="form-control" onchange="this.form.submit()">
                            <option value="nombre" <?php if ($orderBy == 'nombre') echo 'selected'; ?>>Orden Alfabético</option>
                            <option value="numero_inventario" <?php if ($orderBy == 'numero_inventario') echo 'selected'; ?>>Por Número de Inventario</option>
                            <option value="numero_serie" <?php if ($orderBy == 'numero_serie') echo 'selected'; ?>>Por Número de Serie</option>
                            <option value="estado" <?php if ($orderBy == 'estado') echo 'selected'; ?>>Por Estado</option>
                            <option value="ubicacion_actual" <?php if ($orderBy == 'ubicacion_actual') echo 'selected'; ?>>Por Ubicación Actual</option>
                            <option value="fecha_adquisicion" <?php if ($orderBy == 'fecha_adquisicion') echo 'selected'; ?>>Por Fecha de Adquisición</option>
                        </select>

                        <select name="orderDirection" class="form-control" onchange="this.form.submit()">
                            <option value="ASC" <?php if ($orderDirection == 'ASC') echo 'selected'; ?>>Ascendente</option>
                            <option value="DESC" <?php if ($orderDirection == 'DESC') echo 'selected'; ?>>Descendente</option>
                        </select>

                        <select name="bodega" class="form-control" onchange="this.form.submit()">
                            <option value="">Selecciona una Bodega</option>
                            <option value="Bodega 1" <?php if ($bodegaFilter == 'Bodega 1') echo 'selected'; ?>>Bodega 1</option>
                            <option value="Bodega 2" <?php if ($bodegaFilter == 'Bodega 2') echo 'selected'; ?>>Bodega 2</option>
                            <option value="Bodega 3" <?php if ($bodegaFilter == 'Bodega 3') echo 'selected'; ?>>Bodega 3</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <h3>Productos Registrados</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th><a href="?orderBy=nombre&orderDirection=<?php echo $orderDirection; ?>">Nombre</a></th>
                                <th><a href="?orderBy=numero_inventario&orderDirection=<?php echo $orderDirection; ?>">Número de Inventario</a></th>
                                <th><a href="?orderBy=numero_serie&orderDirection=<?php echo $orderDirection; ?>">Número de Serie</a></th>
                                <th><a href="?orderBy=estado&orderDirection=<?php echo $orderDirection; ?>">Estado</a></th>
                                <th><a href="?orderBy=ubicacion_actual&orderDirection=<?php echo $orderDirection; ?>">Ubicación Actual</a></th>
                                <th><a href="?orderBy=fecha_adquisicion&orderDirection=<?php echo $orderDirection; ?>">Fecha de Adquisición</a></th>
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
                                <td><?= $producto['fecha_adquisicion'] ?></td> <!-- Se añadió 'fecha_adquisicion' -->
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-warning">
            No hay productos registrados en la bodega.
        </div>
        <?php endif; ?>
    </div>

    <!-- Pie de página (Footer) -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Fiscalía Regional del Maule | Todos los derechos reservados</p>
    </footer>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
