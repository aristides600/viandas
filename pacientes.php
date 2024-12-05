<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>
    <!-- Inclusión del encabezado -->
    <?php include 'header.php'; ?>

    <div id="app" class="container mt-5">
    <h2>Gestión de Pacientes</h2>


        <!-- Botón para nuevo paciente -->
        <div class="mb-3">
            <button class="btn btn-primary btn-sm" @click="nuevoPaciente">
                <i class="bi bi-plus"></i> Nuevo Paciente
            </button>
        </div>

        <!-- Campo de búsqueda -->
        <div class="mb-3">
            <input type="text" v-model="filtro" class="form-control" placeholder="Buscar por DNI o Apellido" aria-label="Campo de búsqueda">
        </div>

        <!-- Tabla de pacientes -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Apellidos</th>
                    <th>Nombres</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Sexo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="paciente in pacientesFiltrados" :key="paciente.id">
                    <td>{{ paciente.dni }}</td>
                    <td>{{ paciente.apellido }}</td>
                    <td>{{ paciente.nombre }}</td>
                    <td>{{ formatearFecha(paciente.fecha_nacimiento) }}</td>
                    <td>{{ paciente.sexo }}</td>
                    <td>
                        <!-- Botón de editar -->
                        <button class="btn btn-info btn-sm" @click="editarPaciente(paciente.id)" title="Editar paciente">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <!-- Botón de eliminar -->
                        <button class="btn btn-danger btn-sm" @click="eliminarPaciente(paciente.id)" title="Eliminar paciente">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Mensaje cuando no hay resultados -->
        <div v-if="pacientesFiltrados.length === 0" class="alert alert-warning text-center" role="alert">
            No se encontraron resultados para la búsqueda.
        </div>
    </div>

    <!-- Inclusión del pie de página -->
    <?php include 'footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="./js/pacientes.js"></script>
</body>

</html>
