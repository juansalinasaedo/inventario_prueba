<?php
// Incluir la librería de PhpSpreadsheet
require 'vendor/autoload.php'; // Asegúrate de que la ruta sea correcta

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

// Incluir el archivo de productoController.php para obtener los productos
include('php/productoController.php');

// Crear una instancia de ProductoController
$productoController = new ProductoController();

// Obtener todos los productos
$productos = $productoController->obtenerProductos();

// Crear una nueva hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Título de la hoja de cálculo
$sheet->setCellValue('A1', 'Reporte de Productos de Bodega');
$sheet->mergeCells('A1:E1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Títulos de las columnas
$sheet->setCellValue('A2', 'Nombre');
$sheet->setCellValue('B2', 'Número de Inventario');
$sheet->setCellValue('C2', 'Número de Serie');
$sheet->setCellValue('D2', 'Estado');
$sheet->setCellValue('E2', 'Ubicación Actual');

// Estilo para los títulos de las columnas
$sheet->getStyle('A2:E2')->getFont()->setBold(true);
$sheet->getStyle('A2:E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A2:E2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$sheet->getStyle('A2:E2')->getFill()->getStartColor()->setRGB('4F81BD'); // Color de fondo azul
$sheet->getStyle('A2:E2')->getFont()->getColor()->setRGB(Color::COLOR_WHITE);

// Rellenar las filas con los productos
$row = 3;
foreach ($productos as $producto) {
    $sheet->setCellValue('A' . $row, $producto['nombre']);
    $sheet->setCellValue('B' . $row, $producto['numero_inventario']);
    $sheet->setCellValue('C' . $row, $producto['numero_serie']);
    $sheet->setCellValue('D' . $row, $producto['estado']);
    $sheet->setCellValue('E' . $row, $producto['ubicacion_actual']);
    
    // Estilo para las filas
    $sheet->getStyle('A' . $row . ':E' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    
    // Alternar el color de fondo de las filas para mejor legibilidad
    if ($row % 2 == 0) {
        $sheet->getStyle('A' . $row . ':E' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A' . $row . ':E' . $row)->getFill()->getStartColor()->setRGB('D9E1F2'); // Color de fondo gris claro
    }
    
    $row++;
}

// Establecer la anchura de las columnas
foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Aplicar filtros automáticos en las columnas
$sheet->setAutoFilter('A2:E2'); // Filtros en las columnas de A a E

// Crear el escritor de Excel
$writer = new Xlsx($spreadsheet);

// Nombre del archivo
$filename = 'reporte_bodega_' . date('Y-m-d') . '.xlsx';

// Establecer encabezados para forzar la descarga del archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Guardar el archivo en el output
$writer->save('php://output');
exit;
?>
