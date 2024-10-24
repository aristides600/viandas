<?php
require_once 'api/autenticacion_abierta.php';
// require_once 'api/autenticacion.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-form {
            width: 100%;
            max-width: 400px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fff;
        }

        /* .form-label {
            display: block;
            text-align: left;
        } */

        .form-label {
            display: block;
            text-align: left;
            padding-left: 2.5cm;
            /* Agregar 2 cm de espacio a la izquierda */
        }

        .custom-size {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            margin-bottom: 10px;
        }

        .input-half {
            width: calc(50% - 5px);
            /* 50% menos el margen para separar los elementos */
        }

        .center-input {
            margin: 0 auto;
            /* Centrar horizontalmente */
        }
    </style>
</head>

<body>
    <div id="app" class="login-container">
        <div class="login-form">
            <a class="navbar-brand" href="#">
                <img src="./img/Logo.png" alt="Logo" width="50" height="40" class="d-inline-block align-text-top">
            </a>
            <h3>Sistema de Dietas</h3>
            <form @submit.prevent="login" class="text-center">
                <div class="mb-3 text-center">
                    <label for="usuario" class="form-label">Usuario:</label>
                    <input type="text" class="form-control custom-size input-half center-input" id="usuario" v-model="usuario" required maxlength="20">
                </div>

                <div class="mb-3 text-center">
                    <label for="clave" class="form-label">Clave:</label>
                    <input type="password" class="form-control custom-size input-half center-input" id="clave" v-model="clave" required maxlength="20">
                </div>
                <button type="submit" class="btn btn-primary custom-size">Iniciar sesión</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="./js/mensajes.js"></script>
    <script src="./js/login.js"></script>
</body>

</html>