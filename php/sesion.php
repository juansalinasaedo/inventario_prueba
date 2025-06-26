<?php
session_start();

// Tiempo máximo de inactividad en segundos (15 minutos)
$timeout = 15 * 60;

// Verificar tiempo de inactividad
if (isset($_SESSION['ultimo_acceso'])) {
    $inactivo = time() - $_SESSION['ultimo_acceso'];
    if ($inactivo > $timeout) {
        session_unset();
        session_destroy();
        header("Location: index.php?sesion=expirada");
        exit;
    }
}

// Actualizar último acceso
$_SESSION['ultimo_acceso'] = time();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
