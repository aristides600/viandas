<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Dietas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div id="app">
        <div class="container mt-5">
            <input type="text" v-model="filtro" class="form-control mb-3" placeholder="Buscar por DNI o Apellido">
            <h2>Dietas de Internados</h2>
            <button @click="abrirModalComida" class="btn btn-primary btn-sm">Imprimir Todas las Etiquetas</button>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sector</th>
                        <th>Apellido</th>
                        <th>Nombre</th>
                        <th>Edad</th>
                        <th>Código</th>
                        <th>Nombre Dieta</th>
                        <th>Observación</th>
                        <th>Fecha Asignada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="dieta in pacientesFiltrados" :key="dieta.id">
                        <td>{{ dieta.nombre_sector || '-' }}</td>
                        <td>{{ dieta.apellido_paciente || '-' }}</td>
                        <td>{{ dieta.nombre_paciente || '-' }}</td>
                        <td>{{ dieta.edad || '-' }}</td>
                        <td>{{ dieta.codigo_dieta || '-' }}</td>
                        <td>{{ dieta.nombre_dieta || '-' }}</td>
                        <td>{{ dieta.observacion || '-' }}</td>
                        <td>{{ formatearFecha(dieta.fecha_consumo) || '-' }}</td>
                        <td>
                            <button class="btn btn-info btn-sm" @click="editarDieta(dieta.id)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" @click="eliminarDieta(dieta.id)">
                                <i class="bi bi-trash"></i>
                            </button>
                            <button class="btn btn-secondary btn-sm" @click="verDietas(dieta.internacion_id)">
                                <i class="bi bi-eye"></i>
                            </button>
                            <!-- <button class="btn btn-secondary btn-sm" @click="imprimirEtiqueta(dieta.internacion_id)">
                                <i class="bi bi-printer"></i>
                            </button> -->
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="modal fade" id="modalComida" tabindex="-1" aria-labelledby="modalComidaLabel" aria-hidden="true" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalComidaLabel">Seleccionar Comida</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <p>¿Qué tipo de comida deseas seleccionar?</p>
                            <!-- Dropdown para seleccionar la comida -->
                            <select v-model="comidaSeleccionada" class="form-select" aria-label="Seleccionar Comida">
                                <option v-for="comida in comidas" :key="comida.id" :value="comida.id">
                                    {{ comida.nombre }}
                                </option>
                            </select>
                            <!-- Botón para procesar el consumo -->
                            <button @click="procesarConsumo" class="btn btn-primary w-100 mt-2">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>


            <button class="btn btn-primary mt-3" @click="generarPDF">Generar PDF</button>
        </div>
        <div v-if="pacientesFiltrados.length === 0">
            <p>No se encontraron resultados para la búsqueda.</p>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.15/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="./js/pacientes_dietas.js"></script>
</body>

</html>