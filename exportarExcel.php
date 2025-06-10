<?php
require 'php/productoController.php';

// Obtener filtros desde GET
$orderBy = $_GET['orderBy'] ?? 'nombre';
$orderDirection = $_GET['orderDirection'] ?? 'ASC';
$bodegaFilter = $_GET['bodega'] ?? '';

// Obtener productos
$productoController = new ProductoController();
$productos = $productoController->obtenerProductos($orderBy, $orderDirection, $bodegaFilter, 10000, 0);

// Encabezados para forzar descarga como Excel con codificación UTF-8
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=productos_bodega.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Especificar codificación UTF-8 y estilos para Excel
echo "\xEF\xBB\xBF"; // BOM para Excel UTF-8
?>

<table border="1" style="border-collapse: collapse;">
    <thead>
        <tr style="background-color: #f2f2f2; font-weight: bold;">
            <th style="padding: 6px;">Nombre</th>
            <th style="padding: 6px;">N° Inventario</th>
            <th style="padding: 6px;">N° Serie</th>
            <th style="padding: 6px;">Estado</th>
            <th style="padding: 6px;">Ubicación</th>
            <th style="padding: 6px;">Fecha de Adquisición</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $producto): ?>
        <tr>
            <td style="padding: 5px;"><?= htmlspecialchars($producto['nombre']) ?></td>
            <td style="padding: 5px;"><?= $producto['numero_inventario'] ?></td>
            <td style="padding: 5px;"><?= $producto['numero_serie'] ?></td>
            <td style="padding: 5px;"><?= $producto['estado'] ?></td>
            <td style="padding: 5px;"><?= htmlspecialchars($producto['ubicacion_actual']) ?></td>
            <td style="padding: 5px;"><?= $producto['fecha_adquisicion'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
