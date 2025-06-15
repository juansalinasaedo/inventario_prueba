<?php
session_start(); // Iniciar sesión para almacenar los resultados
$searchTerm = ''; // Inicializar por defecto
// Incluir el archivo de conexión a la base de datos
include_once 'php/db.php';
require 'vendor/autoload.php'; // Incluir PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Conexión a la base de datos
$database = new Database();
$pdo = $database->getConnection();

// Si se accede sin buscar ni exportar, limpiar búsqueda anterior
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    unset($_SESSION['searchResults']);
    $searchResults = [];
} else {
    // Procesar la búsqueda
    if (isset($_POST['search'])) {
        $searchTerm = $_POST['search'];
        
        $query = "SELECT * FROM productos WHERE nombre LIKE :searchTerm OR numero_inventario LIKE :searchTerm";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['searchTerm' => "%$searchTerm%"]);
        
        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $_SESSION['searchResults'] = $searchResults;
    } else {
        $searchResults = isset($_SESSION['searchResults']) ? $_SESSION['searchResults'] : [];
    }
}

// Exportar los resultados a Excel
if (isset($_POST['export_excel']) && !empty($searchResults)) {
    // Limpiar resultados de la sesión después de exportar
    unset($_SESSION['searchResults']);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Agregar encabezados a las columnas
    $sheet->setCellValue('A1', 'Nombre');
    $sheet->setCellValue('B1', 'Número de Inventario');
    $sheet->setCellValue('C1', 'Número de Serie');
    $sheet->setCellValue('D1', 'Estado');
    $sheet->setCellValue('E1', 'Ubicación');

    // Llenar los datos de la búsqueda en el archivo Excel
    $row = 2;
    foreach ($searchResults as $producto) {
        $sheet->setCellValue('A' . $row, $producto['nombre']);
        $sheet->setCellValue('B' . $row, $producto['numero_inventario']);
        $sheet->setCellValue('C' . $row, $producto['numero_serie']);
        $sheet->setCellValue('D' . $row, $producto['estado']);
        $sheet->setCellValue('E' . $row, $producto['ubicacion_actual']);
        $row++;
    }

    // Escribir el archivo Excel
    $writer = new Xlsx($spreadsheet);
    $fileName = 'resultados_busqueda.xlsx';
    $writer->save($fileName);

    // Forzar descarga del archivo
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit;
}

// Si se accede sin buscar ni exportar, limpiar búsqueda anterior
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    unset($_SESSION['searchResults']);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario Fiscalía</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .card:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }

        /* Animación en el botón de búsqueda */
        .btn-search {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease-in-out;
        }

        .btn-search::before {
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

        .btn-search:hover::before {
            transform: translate(-50%, -50%) scale(1);
        }
    </style>
</head>

<body>
    <!-- Cabecera -->
    <header class="bg-primary text-white text-center py-4">
        <h1>Bienvenido al Sistema de Inventarios</h1>
        <p class="lead">Gestiona tus productos de manera eficiente y sencilla.</p>
    </header>

    <div class="container my-5">
        <!-- Formulario de búsqueda -->
        <div class="row mb-4">
            <div class="col-md-12 text-center">
            <form method="POST" action="index.php">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar en el sistema..." aria-label="Buscar en el sistema" value="<?= htmlspecialchars($searchTerm) ?>" required>
                    <button class="btn btn-outline-secondary btn-search" type="submit">
                        <i class="fas fa-search"></i> Buscar
                    </button>

                    <?php if (!empty($searchResults) || (!empty($searchTerm) && $_SERVER['REQUEST_METHOD'] == 'POST')): ?>
                        <a href="index.php" class="btn btn-outline-danger ms-2">
                            <i class="fas fa-times-circle"></i> Limpiar búsqueda
                        </a>
                    <?php endif; ?>
                </div>
            </form>
            </div>
        </div>

        <!-- Mostrar resultados de búsqueda -->
        <?php if (!empty($searchResults)): ?>
        <!-- Botón para exportar los resultados a Excel -->
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <form method="POST" action="index.php">
                    <button class="btn btn-success" type="submit" name="export_excel">
                        <i class="fas fa-file-excel"></i> Exportar a Excel
                    </button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3>Resultados de la Búsqueda</h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Número de Inventario</th>
                                <th>Número de Serie</th>
                                <th>Estado</th>
                                <th>Ubicación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($searchResults as $producto): ?>
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
                </div>
            </div>
        </div>
        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search']) && trim($_POST['search']) !== ''): ?>
            <div class="alert alert-warning">
                No se encontraron resultados para la búsqueda.
            </div>
        <?php endif; ?>
    </div>

    <!-- Navegación de Módulos -->
    <div class="container my-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-plus-circle"></i> Registrar Producto</h5>
                        <p class="card-text">Agrega un nuevo producto al inventario.</p>
                        <a href="registroActivo.php" class="btn btn-primary">Registrar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-search"></i> Consultar Inventario</h5>
                        <p class="card-text">Visualiza el estado de los productos registrados.</p>
                        <a href="consultaBodega.php" class="btn btn-success">Consultar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-file-excel"></i> Generar Reporte</h5>
                        <p class="card-text">Genera un reporte de productos en formato Excel.</p>
                        <a href="entregaReporte.php" class="btn btn-info">Generar Reporte</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-exchange-alt"></i> Registrar Movimiento</h5>
                        <p class="card-text">Registra entradas, salidas, ajustes o traslados de productos.</p>
                        <a href="registroMovimiento.php" class="btn btn-warning">Registrar Movimiento</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-history"></i> Ver Movimientos</h5>
                        <p class="card-text">Consulta el historial de movimientos realizados.</p>
                        <a href="consultaMovimientos.php" class="btn btn-secondary">Ver Movimientos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Fiscalía Regional del Maule | Todos los derechos reservados</p>
    </footer>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
