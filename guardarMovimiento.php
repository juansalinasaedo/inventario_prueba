<?php
include_once 'php/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = $_POST['producto_id'] ?? null;
    $tipo = $_POST['tipo'] ?? '';
    $motivo = $_POST['motivo'] ?? '';
    $usuario_id = $_POST['responsable_id'] ?? null;
    $fecha = $_POST['fecha_movimiento'] ?? date('Y-m-d');

    if (!$producto_id || !$tipo || !$usuario_id) {
        die('Faltan campos obligatorios.');
    }

    // Conectar a la base de datos
    $db = new Database();
    $conn = $db->getConnection();

    // Obtener ubicación actual del producto
    $stmtProd = $conn->prepare("SELECT ubicacion_actual FROM productos WHERE id = ?");
    $stmtProd->execute([$producto_id]);
    $producto = $stmtProd->fetch(PDO::FETCH_ASSOC);
    $ubicacionAnterior = $producto ? $producto['ubicacion_actual'] : null;
    $ubicacionNueva = $ubicacionAnterior; // Por defecto

    // Si es traslado, se espera un campo ubicacion_nueva (opcional de momento)
    if ($tipo === 'traslado' && isset($_POST['ubicacion_nueva'])) {
        $ubicacionNueva = $_POST['ubicacion_nueva'];
    }

    // Insertar movimiento
    $stmt = $conn->prepare("INSERT INTO movimientos 
        (producto_id, tipo, cantidad, estado_producto, ubicacion_anterior, ubicacion_nueva, fecha, usuario_id, observaciones) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        $producto_id,
        strtolower($tipo),
        1, // cantidad fija por ahora
        'bueno', // estado predeterminado
        $ubicacionAnterior,
        $ubicacionNueva,
        $fecha,
        $usuario_id,
        $motivo
    ]);

    // Si hubo traslado, actualizar ubicación del producto
    if ($tipo === 'traslado' && $ubicacionNueva && $ubicacionNueva !== $ubicacionAnterior) {
        $update = $conn->prepare("UPDATE productos SET ubicacion_actual = ? WHERE id = ?");
        $update->execute([$ubicacionNueva, $producto_id]);
    }

    header('Location: consultaMovimientos.php');
    exit;
} else {
    echo "Acceso no permitido.";
}
?>
