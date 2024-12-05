<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>

    <?php include 'header.php'; ?>
    <div id="app" class="container mt-5">
        <h1>Gestión de Pacientes</h1>
        <div class="mt-3">
            <button class="btn btn-sm btn-primary me-2" @click="nuevoPaciente">
                <i class="bi bi-plus"></i> Nuevo Paciente
            </button>
        </div>
        <!-- Buscador -->
        <input type="text" v-model="filtro" class="form-control mb-3" placeholder="Buscar por DNI o Apellido">
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
                <tr v-for="paciente in pacientes" :key="paciente.id">
                    <td>{{ paciente.dni }}</td>
                    <td>{{ paciente.apellido }}</td>
                    <td>{{ paciente.nombre }}</td>
                    <td>{{ formatearFecha(paciente.fecha_nacimiento) }}</td>
                    <td>{{ paciente.sexo }}</td>

                    <td>
                        <!-- Botón de editar -->
                        <button class="btn btn-info btn-sm" @click="editarPaciente(paciente.id)">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <!-- Botón de eliminar -->
                        <button class="btn btn-danger btn-sm" @click="eliminarPaciente(paciente.id)">
                            <i class="bi bi-trash"></i>
                        </button>
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