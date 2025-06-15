
<?php
include_once 'db.php';

class UsuarioController {
    public function obtenerUsuarios() {
        $db = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("SELECT id, nombre FROM usuarios");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
