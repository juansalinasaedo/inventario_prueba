<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('php/productoController.php');
    $producto = new ProductoController();
    $producto->crearProducto();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario Oficina</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJv3fUs0hL2jG9NN15hHmwqzF5fZ2XlDk2jt4jj2vYJ9BhHboHXyl56yN7kS" crossorigin="anonymous">
</head>
<body>
    <header>
        <h1>Bienvenido al Sistema de Inventarios de la Fiscalía Regional del Maule</h1>
        <nav>
            <ul>
                <li><a href="registroActivo.php">Registrar Activo</a></li>
                <li><a href="consultaBodega.php">Consultar Bodega</a></li>
                <li><a href="entregaReporte.php">Generar Reporte</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Opciones disponibles</h2>
        <p>Selecciona una de las opciones en el menú para interactuar con el sistema.</p>
    </section>

    <footer>
        <p>&copy; 2025 Fiscalía Regional del Maule</p>
    </footer>

<!-- Bootstrap JS y Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>    
</body>
</html>
