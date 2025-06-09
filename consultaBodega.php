<?php
// Incluir el archivo de productoController.php para utilizar la clase ProductoController
include('php/productoController.php');

// Crear una instancia de ProductoController
$productoController = new ProductoController();

// Obtener todos los productos
$productos = $productoController->obtenerProductos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Bodega</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJv3fUs0hL2jG9NN15hHmwqzF5fZ2XlDk2jt4jj2vYJ9BhHboHXyl56yN7kS" crossorigin="anonymous">
</head>
<body>
    <header>
        <h1>Consulta de Bodega</h1>
    </header>

    <section>
        <!-- Verificar si hay productos disponibles -->
        <?php if (!empty($productos)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Número de Inventario</th>
                        <th>Número de Serie</th>
                        <th>Estado</th>
                        <th>Ubicación Actual</th>
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
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br><br>
            <a href="index.php">Volver</a>  
        <?php else: ?>
            <p>No hay productos registrados en la bodega.</p>
        <?php endif; ?>
    </section>

    <footer>
        <p>&copy; 2025 Fiscalía Regional del Maule</p>
    </footer>

<!-- Bootstrap JS y Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
