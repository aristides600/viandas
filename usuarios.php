<?php
// require_once 'api/autenticacion.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">

</head>

<body>
    <?php include 'header.php'; ?>

    <div id="app" class="container mt-5">
        <h1>Gestión de Usuarios</h1>
        <button class="btn btn-primary mb-3" @click="mostrarFormulario()">Crear Usuario</button>

        <!-- Formulario de Usuario -->
        <div v-if="mostrarForm" class="mt-4">
            <h3>{{ form.id ? 'Editar Usuario' : 'Crear Usuario' }}</h3>
            <form @submit.prevent="guardarUsuario">
                <div class="mb-3">
                    <label for="dni" class="form-label">DNI</label>
                    <input type="text" v-model="form.dni" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" v-model="form.apellido" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" v-model="form.nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input type="text" v-model="form.usuario" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="clave" class="form-label">Clave</label>
                    <input type="password" v-model="form.clave" class="form-control" :required="!form.id">
                </div>
                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <select v-model="form.rol_id" class="form-select" required>
                        <option v-for="rol in roles" :value="rol.id" :key="rol.id">{{ rol.nombre }}</option>
                    </select>
                </div>
<!-- 
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select v-model="form.estado" class="form-select" required>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div> -->
                <button type="submit" class="btn btn-success">{{ form.id ? 'Actualizar' : 'Guardar' }}</button>
                <button type="button" class="btn btn-secondary" @click="mostrarForm = false">Cancelar</button>
            </form>
        </div>


        <!-- Tabla de Usuarios -->
        <div v-if="usuarios.length > 0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>DNI</th>
                        <th>Apellido</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <!-- <th>Estado</th> -->
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="usuario in usuarios" :key="usuario.id">
                        <td>{{ usuario.dni }}</td>
                        <td>{{ usuario.apellido }}</td>
                        <td>{{ usuario.nombre }}</td>
                        <td>{{ usuario.usuario }}</td>
                        <td>{{ usuario.rol }}</td>
                        <!-- <td>{{ usuario.estado ? 'Activo' : 'Inactivo' }}</td> -->
                        <td>
                            <button class="btn btn-warning btn-sm" @click="editarUsuario(usuario)">Editar</button>
                            <!-- <button class="btn btn-danger btn-sm" @click="eliminarUsuario(usuario.id)">Eliminar</button> -->
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else>
            <p class="text-muted">No hay usuarios registrados.</p>
        </div>


    </div>
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.33/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="./js/chequeo_permiso.js"></script>
    <script src="./js/usuarios.js"></script>
</body>

</html>