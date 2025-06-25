<?php
include_once('db.php');

class ProductoController {

    // Obtener productos con opciones de ordenamiento, filtro por bodega y paginación
    public function obtenerProductos($orderBy = 'nombre', $orderDirection = 'ASC', $bodegaFilter = '', $limit = 10, $offset = 0) {
        $database = new Database();
        $pdo = $database->getConnection();

        $columnasPermitidas = ['nombre', 'numero_inventario', 'numero_serie', 'estado', 'ubicacion_actual', 'fecha_adquisicion'];
        $direccionesPermitidas = ['ASC', 'DESC'];

        if (!in_array($orderBy, $columnasPermitidas)) {
            $orderBy = 'nombre';
        }

        if (!in_array(strtoupper($orderDirection), $direccionesPermitidas)) {
            $orderDirection = 'ASC';
        }

        $query = "SELECT * FROM productos WHERE 1";

        if (!empty($bodegaFilter)) {
            $query .= " AND ubicacion_actual LIKE :bodegaFilter";
        }

        $query .= " ORDER BY $orderBy $orderDirection LIMIT :limit OFFSET :offset";

        $stmt = $pdo->prepare($query);

        if (!empty($bodegaFilter)) {
            $stmt->bindValue(':bodegaFilter', "%$bodegaFilter%");
        }

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todas las bodegas disponibles (ubicaciones únicas)
    public function obtenerBodegas() {
        $database = new Database();
        $pdo = $database->getConnection();

        $query = "SELECT DISTINCT ubicacion_actual AS nombre FROM productos";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener productos para formularios (solo id y nombre)
    public function obtenerProductosParaFormulario() {
        $database = new Database();
        $pdo = $database->getConnection();
    
        // Filtra por productos con estado 'nuevo' o 'bueno'
        $query = "SELECT id, nombre, numero_inventario 
                  FROM productos 
                  WHERE estado IN ('nuevo', 'bueno') 
                  ORDER BY nombre ASC";
    
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarProducto($nombre, $numero_inventario, $numero_serie, $estado, $fecha_adquisicion, $ubicacion_actual) {
        $database = new Database();
        $pdo = $database->getConnection();
    
        $query = "INSERT INTO productos (nombre, numero_inventario, numero_serie, estado, fecha_adquisicion, ubicacion_actual)
                  VALUES (:nombre, :numero_inventario, :numero_serie, :estado, :fecha_adquisicion, :ubicacion_actual)";
    
        $stmt = $pdo->prepare($query);
    
        return $stmt->execute([
            ':nombre' => $nombre,
            ':numero_inventario' => $numero_inventario,
            ':numero_serie' => $numero_serie,
            ':estado' => $estado,
            ':fecha_adquisicion' => $fecha_adquisicion,
            ':ubicacion_actual' => $ubicacion_actual
        ]);
    }

    public function existeProducto($numero_inventario, $numero_serie) {
        $database = new Database();
        $pdo = $database->getConnection();
    
        $query = "SELECT COUNT(*) FROM productos 
                  WHERE numero_inventario = :numero_inventario 
                     OR numero_serie = :numero_serie";
    
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':numero_inventario' => $numero_inventario,
            ':numero_serie' => $numero_serie
        ]);
    
        return $stmt->fetchColumn() > 0;
    }
    
}
