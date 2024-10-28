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
    <title>Sistema de Gestión de Dietas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .logo-title-container {
            display: flex;
            align-items: center;
            background-color: #007bff;
            padding: 10px;
            color: white;
        }

        .logo-title-container img {
            height: 50px;
            margin-right: 10px;
        }

        .logout-button,
        .change-password-button {
            background-color: #007bff;
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .logout-button:hover,
        .change-password-button:hover {
            background-color: #0056b3;
        }

        .logout-button i,
        .change-password-button i {
            margin-right: 5px;
        }

        .card-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .card {
            width: 18rem;
            text-align: center;
        }

        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-header i {
            margin-right: 10px;
        }

        .card-body {
            padding: 1.25rem;
        }

        .navbar {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .nav-link {
            color: #343a40;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #007bff;
        }

        .user-info {
            margin-right: 10px;
            display: flex;
            align-items: center;
        }

        .user-info span {
            margin-right: 15px;
            font-weight: bold;
        }

        html,
        body {
            height: 100%;
            margin: 0;
        }

        #app {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex-grow: 1;
        }

        footer {
            background-color: #007bff;
            border-top: 1px solid #dee2e6;
            padding: 10px 0;
            position: relative;
            width: 100%;
            color: white;
        }

        .btn-logout {
            margin-left: auto;
            background-color: #dc3545;
        }

        .btn-logout:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <div id="app" class="container-fluid p-0">
        <!-- Header -->
        <div class="logo-title-container">
            <img src="./img/logo.png" alt="Logo">
            <h3>Sistema de Gestión de Dietas</h3>
        </div>

        <!-- Menú de navegación -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="pacientes.php">Pacientes</a></li>
                        <li class="nav-item"><a class="nav-link" href="internados.php">Internados</a></li>
                        
                        <li class="nav-item"><a class="nav-link" href="pacientes_dietas.php">Dietas</a></li>
                        <li class="nav-item"><a class="nav-link" href="etiquetas.php">Etiquetas</a></li>
                        <li class="nav-item"><a class="nav-link" href="reportes.php">Reportes</a></li>
                        <li class="nav-item"><a class="nav-link" href="usuarios.php">Usuarios</a></li>
                    </ul>
                    <!-- Información de usuario -->
                    <div class="user-info ms-auto d-flex align-items-center">
                        <span><?php echo $nombre . ' ' . $apellido; ?></span>
                        
                        <form action="logout.php" method="post" class="d-inline">
                            <button class="logout-button ms-2">
                                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                            </button>
                        </form>
                        <button class="change-password-button">
                            <i class="bi bi-key"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenido Principal -->
        <main class="container mt-4">
            <!-- <h1 class="text-center">Sistema de Gestión de Dietas</h1> -->

            <!-- Tarjetas responsivas -->
            <div class="card-container mt-4">
                <div class="card">
                    <div class="card-header"><i class="bi bi-person"></i> Pacientes</div>
                    <div class="card-body">
                        <a href="pacientes.php" class="btn btn-primary">Ir a Pacientes</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><i class="bi bi-hospital"></i> Internados</div>
                    <div class="card-body">
                        <a href="internados.php" class="btn btn-primary">Ir a Internados</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><i class="bi bi-tag"></i> Etiquetas</div>
                    <div class="card-body">
                        <a href="etiquetas.php" class="btn btn-primary">Ir a Etiquetas</a>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="text-center">
            <p>&copy; Copyright 2024 | Ministerio de Salud | Desarrollado por InfoSys</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
