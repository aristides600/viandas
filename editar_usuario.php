<?php
require_once 'api/autenticacion.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <div id="app" class="container mt-5">
        <div v-if="usuario_id !== null"></div>
        <h2>Editar Usuario</h2>
        <!-- Formulario para agregar un nuevo usuario -->
        <form @submit.prevent="guardarEdicion">
            <div class="mb-3">
                <label for="dni" class="form-label">Dni:</label>
                <input type="number" class="form-control" id="dni" v-model="usuario.dni" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido:</label>
                <input type="text" class="form-control" id="apellido" v-model="usuario.apellido" required>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" v-model="usuario.nombre" required>
            </div>

            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario:</label>
                <input type="text" class="form-control" id="usuario" v-model="usuario.usuario" required>
            </div>
            <div class="mb-3">
                <label for="clave" class="form-label">Clave:</label>
                <input type="password" class="form-control" id="clave" v-model="usuario.clave" required>
            </div>

            <div class="mb-3">
                <label for="rol" class="form-label">Rol:</label>
                <select class="form-select" id="rol" v-model="usuario.rol_id">
                    <option value="">Selecciona una rol</option>
                    <option v-for="rol in roles" :value="rol.id">{{ rol.nombre }}</option>
                </select>
            </div>

            <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="index.php" class="btn btn-danger">Salir</a>
            </div>
        </form>

    </div>
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="./js/chequeo_permiso.js"></script>

    <script src="./js/mensajes.js"></script>
    <script src="./js/editar_usuario.js"></script>

</body>

</html>