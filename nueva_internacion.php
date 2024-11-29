<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Internaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>

    <?php include 'header.php'; ?>
    <div id="app" class="container mt-4">
        <h1>Nueva Internación</h1>

        <!-- Formulario para agregar una nueva internación -->
        <form @submit.prevent="agregarInternacion">
            <div class="mb-3">
                <label for="busqueda" class="form-label">Buscar Paciente (DNI o Apellido)</label>
                <input type="text" v-model="busqueda" @input="buscarPacientes" class="form-control" id="busqueda" placeholder="Ingrese DNI o Apellido">
            </div>


            <!-- Mensaje si no hay coincidencias -->
            <div v-if="sinCoincidencias" class="alert alert-warning" role="alert">
                No se encontraron pacientes con ese DNI o apellido.
            </div>

            <!-- Tabla de coincidencias -->
            <div v-if="pacientes.length > 0" class="mb-3">
                <h5>Coincidencias</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>DNI</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="paciente in pacientes" :key="paciente.id">
                            <td>{{ paciente.dni }}</td>
                            <td>{{ paciente.nombre }}</td>
                            <td>{{ paciente.apellido }}</td>
                            <td>{{ paciente.fecha_nacimiento }}</td>
                            <td>
                                <button type="button" class="btn btn-primary" @click="seleccionarPaciente(paciente)">Seleccionar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="pacienteSeleccionado" class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">{{ pacienteSeleccionado.nombre }} {{ pacienteSeleccionado.apellido }}</h5>
                    <p class="card-text">DNI: {{ pacienteSeleccionado.dni }}</p>
                    <button class="btn btn-secondary" @click="limpiarPaciente">Limpiar Selección</button>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="sector_id" class="form-label">Seleccionar Sector</label>
                <select class="form-control" v-model="nuevaInternacion.sector_id" required>
                    <option v-for="sector in sectores" :key="sector.id" :value="sector.id">
                        {{ sector.nombre }}
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label for="diagnostico" class="form-label">Diagnóstico</label>
                <input type="text" class="form-control" v-model="nuevaInternacion.diagnostico" required>
            </div>

            <button type="submit" class="btn btn-primary">Agregar Internación</button>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="./js/nueva_internacion.js"></script>
</body>

</html>