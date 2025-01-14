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
    <div id="app" class="container mt-2">
        <h2>Internaciones</h2>

        <div class="d-flex align-items-center">
            <button class="btn btn-sm btn-primary me-5" @click="nuevaInternacion">
                <i class="bi bi-plus"></i> Nueva Internación
            </button>
            <label class="me-3">
                <input type="radio" v-model="filtroEstado" value="pendiente"> Internados
            </label>
            <label>
                <input type="radio" v-model="filtroEstado" value="cerrada"> Altas
            </label>
        </div>
        <!-- <div class="d-flex align-items-center">
            <label class="me-3">
                <input type="radio" v-model="filtroRevisado" value="todas"> Todas
            </label>
            <label class="me-3">
                <input type="radio" v-model="filtroRevisado" value="revisadas"> Revisadas
            </label>
            <label>
                <input type="radio" v-model="filtroRevisado" value="no_revisadas"> No Revisadas
            </label>
        </div> -->



        <!-- Buscador -->
        <input type="text" v-model="filtro" class="form-control mb-3" placeholder="Buscar por DNI o Apellido o Sector">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>D.N.I.</th>
                    <th>Paciente</th>
                    <!-- <th>Nombres</th> -->
                    <th>Sector</th>
                    <th>Cama</th>
                    <th>Ingreso</th>
                    <!-- <th>Alta</th> -->
                    <th>Diagnóstico</th>
                    <th>Observación</th>
                    <!-- <th>Acciones</th> -->
                </tr>
            </thead>
            <tbody>
                <tr v-for="(internacion, index) in internaciones" :key="internacion.id + '-' + index">
                    <td>{{ internacion.dni }}</td>
                    <td>{{ internacion.apellido }}, {{ internacion.nombre }}</td>
                    <!-- <td>{{ internacion.nombre }}</td> -->
                    <td>{{ internacion.sector_nombre }}</td>
                    <td>{{ internacion.cama }}</td>
                    <td>{{ formatearFecha(internacion.fecha_ingreso) }}</td>
                    <!-- <td>{{ formatearFecha(internacion.fecha_egreso ? internacion.fecha_egreso : '-') }}</td> -->
                    <td>{{ internacion.diagnostico }}</td>
                    <td>{{ internacion.observacion }}</td>
                    <td>
                        <!-- Grupo de botones -->
                        <div class="d-flex gap-1">
                            <!-- Mostrar botones solo si no está cerrada -->
                            <template v-if="filtroEstado !== 'cerrada'">
                                <button class="btn btn-warning btn-sm"
                                    @click="dietaInternacion(internacion.id)"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Agregar dieta">
                                    <i class="bi bi-plus"></i>
                                </button>
                                <button class="btn btn-info btn-sm"
                                    @click="editarInternacion(internacion.id)"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Editar internación">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-success btn-sm"
                                    @click="altaInternacion(internacion.id)"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Dar de alta">
                                    <i class="bi bi-box-arrow-up"></i>
                                </button>



                            </template>

                            <!-- Mostrar botón Detalles solo si está cerrada -->
                            <!-- <button v-if="filtroEstado === 'cerrada'" class="btn btn-primary btn-sm" @click="detallesInternacion(internacion.id)">
                                <i class="bi bi-eye"></i>
                            </button> -->

                        </div>
                    </td>
                    <!-- <td>
                        <input type="checkbox"
                            :checked="internacion.revisado"
                            @change="marcarRevisado(internacion.id, $event.target.checked)">
                    </td> -->

                </tr>
            </tbody>

        </table>
    </div>
    <?php include 'footer.php'; ?>


    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="./cdn/vue.global.js"></script>
    <script src="./cdn/axios.min.js"></script>
    <script src="./cdn/sweetalert2@11.js"></script>
    <script src="./js/internados.js"></script>
</body>

</html>