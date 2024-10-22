<?php
require_once 'api/autenticacion.php';
// require_once 'api/permisos.php';

// Verificar si la sesión ya está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario';
$apellido = isset($_SESSION['apellido']) ? $_SESSION['apellido'] : '';
?>
<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Header con Logo y Menú -->
    <header class="bg-primary py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <!-- Logo -->
            <div>
                <img src="./img/logo.png" alt="Logo" height="50">
            </div>

            <!-- Menú de Navegación -->
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="pacientes.php">Pacientes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="internaciones.php">Internaciones</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link text-white" href="etiquetas.php">Etiquetas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="reportes.php">Reportes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="usuarios.php"> Usuarios

                                </a>

                            </li>

                        </ul>
                        <!-- Información del usuario y botones a la derecha -->
                        <div class="d-flex ms-auto align-items-center">
                            <div class="user-info">
                                <?php
                                echo htmlspecialchars($nombre . ' ' . htmlspecialchars($apellido));
                                ?>
                            </div>

                            <!-- Botón de cerrar sesión -->
                            <a href="logout.php" class="logout-button ms-3">
                                <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                            </a>

                            <!-- Botón de cambiar contraseña -->
                            <button class="change-password-button ms-3" type="button" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="bi bi-key-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Contenido Principal -->
    <div class="container mt-4">
        <h1>Sistema de Gestión de Viandas</h1>
        <p>Utiliza el menú para navegar a las diferentes secciones del sistema.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>