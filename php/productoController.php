<?php
// Incluir el archivo de conexión a la base de datos
include_once('db.php');
class ProductoController {
    public function obtenerProductos($orderBy = 'nombre', $orderDirection = 'ASC', $bodegaFilter = '') {
        // Conexión a la base de datos
        include_once 'db.php';
        $database = new Database();
        $pdo = $database->getConnection();

        // Consulta SQL dinámica con ordenación
        $query = "SELECT * FROM productos WHERE 1";

        // Si hay un filtro por bodega
        if ($bodegaFilter) {
            $query .= " AND ubicacion_actual LIKE :bodegaFilter";
        }

        // Añadir la parte de ordenación
        $query .= " ORDER BY $orderBy $orderDirection";

        // Preparar la consulta
        $stmt = $pdo->prepare($query);

        // Vincular los parámetros
        if ($bodegaFilter) {
            $stmt->bindValue(':bodegaFilter', "%$bodegaFilter%");
        }

        // Ejecutar la consulta
        $stmt->execute();

        // Devolver los resultados
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
