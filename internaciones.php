<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Internaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/vue@3"></script>
</head>

<body>
    <div id="app" class="container mt-4">
        <h1>Gestión de Internaciones</h1>
        <div>
            <button class="btn btn-sm btn-primary me-2" @click="nuevaInternacion">
                <i class="bi bi-plus"></i> Nueva Internacion
            </button>
        </div>

        <!-- Tabla para mostrar internaciones -->
        <h2 class="mt-5">Lista de Internaciones</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Profesional</th>
                    <th>Sector</th>
                    <th>Fecha Ingreso</th>
                    <th>Fecha Egreso</th>
                    <th>Diagnóstico</th>
                    <th>Observación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="internacion in internaciones" :key="internacion.id">
                    <td>{{ internacion.id }}</td>
                    <td>{{ internacion.paciente_nombre }}</td>
                    <td>{{ internacion.profesional_nombre }}</td>
                    <td>{{ internacion.sector_nombre }}</td>
                    <td>{{ internacion.fecha_ingreso }}</td>
                    <td>{{ internacion.fecha_egreso ? internacion.fecha_egreso : '-' }}</td>
                    <td>{{ internacion.diagnostico }}</td>
                    <td>{{ internacion.observacion }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" @click="editarInternacion(internacion)">Editar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="./js/internaciones.js"></script>
</body>

</html>
