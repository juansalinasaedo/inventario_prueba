<?php
include_once 'db.php';

class MovimientoController {
    public function obtenerMovimientos() {
        $db = new Database();
        $pdo = $db->getConnection();
    
        $query = "
            SELECT 
                m.id,
                m.tipo,
                m.cantidad,
                m.estado_producto,
                m.ubicacion_anterior,
                m.ubicacion_nueva,
                m.fecha,
                m.responsable,
                m.observaciones,
                p.nombre AS producto_nombre,
                p.numero_inventario
            FROM movimientos m
            JOIN productos p ON m.producto_id = p.id
            ORDER BY m.fecha DESC
        ";
    
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

 