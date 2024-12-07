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
        <h2>Internaciones</h2>

        <!-- Filtros de estado -->
        <div>
            <label class="me-3">
                <input type="radio" v-model="filtroEstado" value="pendiente"> Internados
            </label>
            <label>
                <input type="radio" v-model="filtroEstado" value="cerrada"> Altas
            </label>
        </div>

        <div class="mt-3">
            <button class="btn btn-sm btn-primary me-2" @click="nuevaInternacion">
                <i class="bi bi-plus"></i> Nueva Internación
            </button>
        </div>

        <!-- Buscador -->
        <input type="text" v-model="filtro" class="form-control mb-3" placeholder="Buscar por DNI o Apellido">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>D.N.I.</th>
                    <th>Apellidos</th>
                    <th>Nombres</th>
                    <th>Sector</th>
                    <th>Fecha de Ingreso</th>
                    <th>Fecha de Alta</th>
                    <th>Diagnóstico</th>
                    <th>Observación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(internacion, index) in internaciones" :key="internacion.id + '-' + index">
                    <td>{{ internacion.dni }}</td>
                    <td>{{ internacion.apellido }}</td>
                    <td>{{ internacion.nombre }}</td>
                    <td>{{ internacion.sector_nombre }}</td>
                    <td>{{ formatearFecha(internacion.fecha_ingreso) }}</td>
                    <td>{{ formatearFecha(internacion.fecha_egreso ? internacion.fecha_egreso : '-') }}</td>
                    <td>{{ internacion.diagnostico }}</td>
                    <td>{{ internacion.observacion }}</td>
                    <td>
                        <!-- Mostrar botones solo si no está cerrada -->
                        <button v-if="filtroEstado !== 'cerrada'" class="btn btn-warning btn-sm" @click="dietaInternacion(internacion.id)">
                            <i class="bi bi-plus"></i> Dieta
                        </button>
                        <button v-if="filtroEstado !== 'cerrada'" class="btn btn-info btn-sm" @click="editarInternacion(internacion.id)">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button v-if="filtroEstado !== 'cerrada'" class="btn btn-success btn-sm" @click="altaInternacion(internacion.id)">
                            <i class="bi bi-box-arrow-up"></i> Alta
                        </button>

                        <!-- Mostrar botón Detalles solo si está cerrada -->
                        <button v-if="filtroEstado === 'cerrada'" class="btn btn-primary btn-sm" @click="detallesInternacion(internacion.id)">
                            <i class="bi bi-eye"></i> Detalles
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
    <script src="./js/internados.js"></script>
</body>

</html>