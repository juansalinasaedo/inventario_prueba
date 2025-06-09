<?php
// Incluir el archivo de conexión a la base de datos
include_once('db.php');

class ProductoController {
    private $conn;

    // Constructor para la conexión a la base de datos
    public function __construct() {
        // Obtener la conexión de la base de datos
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Función para crear un nuevo producto
    public function crearProducto() {
        if (isset($_POST['nombre'], $_POST['numero_inventario'], $_POST['numero_serie'], $_POST['estado'], $_POST['fecha_adquisicion'], $_POST['ubicacion_actual'])) {
            
            $nombre = $_POST['nombre'];
            $numero_inventario = $_POST['numero_inventario'];
            $numero_serie = $_POST['numero_serie'];
            $estado = $_POST['estado'];
            $fecha_adquisicion = $_POST['fecha_adquisicion'];
            $ubicacion_actual = $_POST['ubicacion_actual'];

            if (empty($nombre) || empty($numero_inventario) || empty($numero_serie) || empty($estado) || empty($fecha_adquisicion) || empty($ubicacion_actual)) {
                echo json_encode(["message" => "Todos los campos son obligatorios"]);
                return;
            }

            $query = "INSERT INTO productos (nombre, numero_inventario, numero_serie, estado, fecha_adquisicion, ubicacion_actual) 
                      VALUES (:nombre, :numero_inventario, :numero_serie, :estado, :fecha_adquisicion, :ubicacion_actual)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':numero_inventario', $numero_inventario);
            $stmt->bindParam(':numero_serie', $numero_serie);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':fecha_adquisicion', $fecha_adquisicion);
            $stmt->bindParam(':ubicacion_actual', $ubicacion_actual);

            if ($stmt->execute()) {
                echo json_encode(["message" => "Producto creado correctamente"]);
            } else {
                echo json_encode(["message" => "Error al crear el producto"]);
            }
        } else {
            echo json_encode(["message" => "Todos los campos son obligatorios"]);
        }
    }

    // Función para obtener todos los productos
    public function obtenerProductos() {
        // Consulta para obtener todos los productos
        $query = "SELECT id, nombre, numero_inventario, numero_serie, estado, fecha_adquisicion, ubicacion_actual 
                  FROM productos";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Verificar si hay productos en la base de datos
        if ($stmt->rowCount() > 0) {
            // Devolver los productos como un array
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Si no se encuentran productos, devolver un mensaje
            return [];
        }
    }
}
?>
