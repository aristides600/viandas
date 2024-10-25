<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>

    <?php include 'header.php'; ?>
    <div id="app" class="container mt-5">
        <h1>Gestión de Pacientes</h1>
        <form @submit.prevent="guardarPaciente">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombres</label>
                <input type="text" id="nombre" v-model="paciente.nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellidos</label>
                <input type="text" id="apellido" v-model="paciente.apellido" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="dni" class="form-label">DNI</label>
                <input type="text" id="dni" v-model="paciente.dni" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" id="fecha_nacimiento" v-model="paciente.fecha_nacimiento" class="form-control">
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" id="telefono" v-model="paciente.telefono" class="form-control">
            </div>
            <div class="mb-3">
                <label for="sexo_id" class="form-label">Sexo</label>
                <select id="sexo_id" v-model="paciente.sexo_id" class="form-control">
                    <option value="" disabled>Seleccione Sexo</option>
                    <!-- Carga de sexos -->
                    <option v-for="sexo in sexos" :key="sexo.id" :value="sexo.id">
                        {{ sexo.nombre }}
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">{{ editando ? 'Actualizar' : 'Guardar' }}</button>
            <button type="button" class="btn btn-secondary" @click="resetFormulario">Cancelar</button>
        </form>

        <h2>Lista de Pacientes</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>DNI</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Teléfono</th>
                    <th>Sexo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="paciente in pacientes" :key="paciente.id">
                    <!-- <td>{{ paciente.id }}</td> -->
                    <td>{{ paciente.nombre }}</td>
                    <td>{{ paciente.apellido }}</td>
                    <td>{{ paciente.dni }}</td>
                    <td>{{ formatearFecha(paciente.fecha_nacimiento) }}</td>
                    <td>{{ paciente.telefono }}</td>
                    <td>{{ obtenerNombreSexo(paciente.sexo_id) }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" @click="editarPaciente(paciente)">Editar</button>
                        <button class="btn btn-danger btn-sm" @click="eliminarPaciente(paciente.id)">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php include 'footer.php'; ?>


    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="./js/pacientes.js"></script>

</body>

</html>