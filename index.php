<?php
require_once 'api/autenticacion.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Estilo para el botón de cerrar sesión en rojo */
        .cerrar-sesion {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        /* Estilo para el ícono de la llave */
        i.bi-key-fill {
            color: yellow;
        }
    </style>
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
                                <a class="nav-link text-white" href="pacientes_internados.php">Internados</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="reportes.php">Reportes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="usuarios.php">Usuarios</a>
                            </li>
                        </ul>
                        <!-- Información del usuario y botones a la derecha -->
                        <div class="d-flex ms-auto align-items-center">
                            <div class="user-info text-white me-3">
                                <?php echo htmlspecialchars($nombre . ' ' . htmlspecialchars($apellido)); ?>
                            </div>

                            <!-- Botón de cerrar sesión -->
                            <a href="logout.php" class="btn cerrar-sesion">
                                <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                            </a>

                            <!-- Botón de cambiar contraseña -->
                            <button class="btn btn-warning ms-3" type="button" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="bi bi-key-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Contenido Principal con Tarjetas -->
    <div class="container mt-4">
        <h1>Sistema de Gestión de Dietas</h1>

        <!-- Tarjetas responsivas -->
        <div class="container mt-4">

            <!-- Tarjetas responsivas -->
            <div class="row row-cols-1 row-cols-md-3 g-4 mt-4">
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            <i class="bi bi-person"></i> Pacientes
                        </div>
                        <div class="card-body text-center">
                            <a href="pacientes.php" class="btn btn-primary">Ir a Pacientes</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            <i class="bi bi-hospital"></i> Internaciones
                        </div>
                        <div class="card-body text-center">
                            <a href="internaciones.php" class="btn btn-primary">Ir a Internaciones</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header">
                            <i class="bi bi-tag"></i> Etiquetas
                        </div>
                        <div class="card-body text-center">
                            <a href="etiquetas.php" class="btn btn-primary">Ir a Etiquetas</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal para cambiar contraseña -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Cambiar Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Contraseña Actual</label>
                            <input type="password" class="form-control" id="currentPassword">
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="newPassword">
                        </div>
                        <div class="mb-3">
                            <label for="confirmNewPassword" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control" id="confirmNewPassword">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="./cdn/sweetalert2@10.js"></script>
    <script src="./js/index.js"></script>
</body>

</html>