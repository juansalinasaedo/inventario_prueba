<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - Inventario Fiscalía</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            margin-top: 100px;
            max-width: 400px;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-login:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body>
    <header class="bg-primary text-white text-center py-4">
        <h1>Bienvenido al Sistema de Inventarios</h1>
        <p class="lead">Acceso restringido a usuarios autorizados</p>
    </header>

    <div class="container d-flex justify-content-center">
        <div class="login-container">
            <h4 class="text-center mb-4">Iniciar Sesión</h4>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger text-center">Usuario o contraseña incorrectos.</div>
            <?php endif; ?>

            <?php if (isset($_GET['sesion']) && $_GET['sesion'] === 'expirada'): ?>
                <div class="alert alert-warning text-center">
                    Tu sesión ha expirado por inactividad. Por favor, vuelve a iniciar sesión.
                </div>
            <?php endif; ?>

            <form method="post" action="validar_login.php">
                <div class="mb-3">
                    <label for="user" class="form-label">Nombre de usuario</label>
                    <input type="text" class="form-control" id="user" name="user" required>
                </div>
                <div class="mb-3">
                    <label for="passw" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="passw" name="passw" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="fas fa-sign-in-alt"></i> Ingresar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2025 Fiscalía Regional del Maule | Todos los derechos reservados</p>
    </footer>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
