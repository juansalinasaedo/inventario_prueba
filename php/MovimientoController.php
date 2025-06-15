<?php
include_once 'db.php';

class MovimientoController {
    public function obtenerMovimientos() {
        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("
            SELECT m.*, 
                   p.nombre AS producto_nombre, 
                   p.numero_inventario, 
                   u.nombre AS responsable_nombre
            FROM movimientos m
            JOIN productos p ON m.producto_id = p.id
            JOIN usuarios u ON m.usuario_id = u.id
            ORDER BY m.fecha DESC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

 