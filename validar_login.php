<?php
session_start();

// Obtener datos del formulario
$usr = strtoupper(trim($_POST['user'] ?? ''));
$passwd = $_POST['passw'] ?? '';

// Parámetros LDAP
$dominio = "minpublico.cl";
$host = "172.18.1.7";
$puerto = 389;

// Formato usuario@dominio para autenticación
$usuario = "$usr@$dominio";

// Conectar al servidor LDAP
$conex = ldap_connect($host, $puerto) or die("No se pudo conectar al servidor LDAP");
ldap_set_option($conex, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($conex, LDAP_OPT_REFERRALS, 0);

// Intentar autenticar
if (@ldap_bind($conex, $usuario, $passwd)) {
    include_once 'php/db.php';

    // Verifica si el usuario ya existe en la base de datos
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE nombre = ?");
    $stmt->execute([$usr]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $usr;
        header('Location: dashboard.php');
        exit;
    } else {
        // Crear el usuario automáticamente con email basado en el nombre
        $email = strtolower($usr) . "@minpublico.cl";
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, cargo, email, rol) VALUES (?, '', ?, 'profesional')");
        $stmt->execute([$usr, $email]);

        $newId = $conn->lastInsertId();
        $_SESSION['user_id'] = $newId;
        $_SESSION['username'] = $usr;
        header('Location: dashboard.php');
        exit;
    }
} else {
    // Fallo de autenticación
    header('Location: index.php?error=1');
    exit;
}

ldap_close($conex);