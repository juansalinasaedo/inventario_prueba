<?php
session_start();
include_once 'php/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $producto_id = $_POST['producto_id'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $motivo = trim($_POST['motivo'] ?? '');
    $responsable = trim($_POST['responsable'] ?? '');
    $fecha = $_POST['fecha_movimiento'] ?? '';
    $ubicacion_anterior = $_POST['ubicacion_anterior'] ?? '';
    $ubicacion_nueva = $_POST['ubicacion_nueva'] ?? null;

    $errores = [];

    // Validaciones básicas
    if (empty($producto_id)) $errores[] = "Debe seleccionar un producto.";
    if (empty($tipo)) $errores[] = "Debe seleccionar un tipo de movimiento.";
    if (empty($motivo)) $errores[] = "Debe ingresar un motivo u observación.";
    if (empty($responsable)) $errores[] = "Debe ingresar el nombre del responsable.";
    if (empty($fecha)) {
        $errores[] = "Debe seleccionar una fecha.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        $errores[] = "La fecha no tiene un formato válido (YYYY-MM-DD).";
    } elseif (strtotime($fecha) > strtotime(date('Y-m-d'))) {
        $errores[] = "La fecha de movimiento no puede ser futura.";
    }
    if (($tipo === 'Traslado' || $tipo === 'Ajuste') && empty($ubicacion_nueva)) {
        $errores[] = "Debe especificar la nueva ubicación para movimientos de tipo Traslado o Ajuste.";
    }

    $usuario_id = null;

    $database = new Database();
    $pdo = $database->getConnection();

    // Obtener ubicación actual
    $stmtProducto = $pdo->prepare("SELECT ubicacion_actual FROM productos WHERE id = :producto_id");
    $stmtProducto->execute(['producto_id' => $producto_id]);
    $producto = $stmtProducto->fetch(PDO::FETCH_ASSOC);
    $ubicacion_actual = $producto ? $producto['ubicacion_actual'] : 'Desconocida';
    $ubicacion_anterior = $ubicacion_actual;

    // Validar si se permite el movimiento según ubicación actual
    if (in_array($tipo, ['Salida', 'Traslado', 'Ajuste'])) {
        if ($ubicacion_actual !== 'Bodega UGI' && $ubicacion_nueva !== 'Bodega UGI') {
            $errores[] = "No se puede registrar un movimiento de tipo '$tipo' si el producto no está en 'Bodega UGI'.";
            $errores[] = "Ubicación actual del producto: '$ubicacion_actual'.";
        }
    }

    // Definir ubicación nueva según tipo si corresponde
    if ($tipo === 'Entrada') {
        $ubicacion_nueva = 'Bodega UGI';
    } elseif ($tipo === 'Salida') {
        $ubicacion_nueva = 'Prestado';
    }

    // Si hay errores, volver con mensaje
    if (!empty($errores)) {
        $_SESSION['errores_movimiento'] = $errores;
        header("Location: registroMovimiento.php");
        exit;
    }

    // Insertar movimiento
    $stmt = $pdo->prepare("
        INSERT INTO movimientos 
        (producto_id, tipo, observaciones, responsable, fecha, ubicacion_anterior, ubicacion_nueva, usuario_id) 
        VALUES 
        (:producto_id, :tipo, :observaciones, :responsable, :fecha, :ubicacion_anterior, :ubicacion_nueva, :usuario_id)
    ");
    $stmt->execute([
        'producto_id' => $producto_id,
        'tipo' => $tipo,
        'observaciones' => $motivo,
        'responsable' => $responsable,
        'fecha' => $fecha,
        'ubicacion_anterior' => $ubicacion_anterior,
        'ubicacion_nueva' => $ubicacion_nueva,
        'usuario_id' => $usuario_id
    ]);

    // Actualizar producto
    $stmtUpdate = $pdo->prepare("UPDATE productos SET ubicacion_actual = :nueva WHERE id = :producto_id");
    $stmtUpdate->execute([
        'nueva' => $ubicacion_nueva,
        'producto_id' => $producto_id
    ]);

    $_SESSION['mensaje_exito'] = "Movimiento registrado correctamente.";
    header("Location: consultaMovimientos.php");
    exit;
} else {
    header("Location: registroMovimiento.php");
    exit;
}