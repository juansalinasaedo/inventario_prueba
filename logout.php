<?php
session_start();

// Limpiar todas las variables de sesión
$_SESSION = [];

// Destruir la sesión
session_destroy();

// Redirigir al inicio de sesión
header("Location: index.php");
exit;
