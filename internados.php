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
        <h1>Gestión de Internaciones</h1>
        <div>
            <button class="btn btn-sm btn-primary me-2" @click="nuevaInternacion">
                <i class="bi bi-plus"></i> Nueva Internación
            </button>
        </div>
        <input type="text" v-model="filtro" class="form-control mb-3" placeholder="Buscar por DNI o Apellido">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Paciente</th>
                    <th>Sector</th>
                    <th>Fecha de Ingreso</th>
                    <th>Fecha de Egreso</th>
                    <th>Diagnóstico</th>
                    <th>Observación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(internacion, index) in internaciones" :key="internacion.id + '-' + index">
                    <td>{{ internacion.paciente_nombre }}</td>
                    <td>{{ internacion.sector_nombre }}</td>
                    <td>{{ internacion.fecha_ingreso }}</td>
                    <td>{{ internacion.fecha_egreso ? internacion.fecha_egreso : '-' }}</td>
                    <td>{{ internacion.diagnostico }}</td>
                    <td>{{ internacion.observacion }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" @click="dietaInternacion(internacion.id)">
                            <i class="bi bi-list"></i> Dieta
                        </button>
                        <button class="btn btn-blue btn-sm" @click="editarInternacion(internacion.id)">
                            <i class="bi bi-pencil"></i> Editar
                        </button>
                        <button class="btn btn-danger btn-sm" @click="darAlta(internacion.id)">
                            <i class="bi bi-box-arrow-up"></i> Alta
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="./js/internados.js"></script>
</body>

</html>