<?php
include_once 'db.php'; // Asegúrate que esta ruta sea correcta

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $database = new Database();
    $pdo = $database->getConnection();

    $stmt = $pdo->prepare("SELECT ubicacion_actual FROM productos WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $ubicacion = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($ubicacion) {
        echo json_encode(['ubicacion_actual' => $ubicacion['ubicacion_actual']]);
    } else {
        echo json_encode(['ubicacion_actual' => null]);
    }
} else {
    echo json_encode(['ubicacion_actual' => null]);
}
?>