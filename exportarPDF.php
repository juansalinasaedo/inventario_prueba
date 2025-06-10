<?php
require 'vendor/autoload.php';
require 'php/productoController.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$orderBy = $_GET['orderBy'] ?? 'nombre';
$orderDirection = $_GET['orderDirection'] ?? 'ASC';
$bodegaFilter = $_GET['bodega'] ?? '';

$productoController = new ProductoController();
$productos = $productoController->obtenerProductos($orderBy, $orderDirection, $bodegaFilter, 10000, 0);

$options = new Options();
$options->set('defaultFont', 'Helvetica');
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);

// Ruta al logo (ajústala según dónde esté guardado el archivo)
$logoBase64 = base64_encode(file_get_contents('img/logo-maule.png'));

$html = '
<style>
    body { font-family: Helvetica, sans-serif; font-size: 12px; }
    .header { text-align: center; }
    .logo { width: 150px; margin-bottom: 10px; }
    h2 { text-align: center; margin-top: 0; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    th { background-color: #f2f2f2; }
    .footer { text-align: center; font-size: 10px; margin-top: 30px; color: #555; }
</style>

<div class="header">
    <img src="data:image/png;base64,' . $logoBase64 . '" class="logo">
    <h2>Inventario de Bodega - Fiscalía Regional del Maule</h2>
</div>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>N° Inventario</th>
            <th>N° Serie</th>
            <th>Estado</th>
            <th>Ubicación</th>
            <th>Fecha de Adquisición</th>
        </tr>
    </thead>
    <tbody>';

foreach ($productos as $producto) {
    $html .= '<tr>
        <td>' . htmlspecialchars($producto['nombre']) . '</td>
        <td>' . $producto['numero_inventario'] . '</td>
        <td>' . $producto['numero_serie'] . '</td>
        <td>' . $producto['estado'] . '</td>
        <td>' . htmlspecialchars($producto['ubicacion_actual']) . '</td>
        <td>' . $producto['fecha_adquisicion'] . '</td>
    </tr>';
}

$html .= '
    </tbody>
</table>

<div class="footer">
    Informe generado automáticamente el ' . date('d-m-Y H:i') . '<br>
    Ministerio Público de Chile - Fiscalía Regional del Maule
</div>';

$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('productos_bodega.pdf', ['Attachment' => true]);
