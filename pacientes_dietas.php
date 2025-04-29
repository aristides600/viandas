<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dietas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
    <style>
        .tabla-optimizada {
            margin-left: 0;
            margin-right: auto;
            font-size: 0.9rem;
            /* Reduce el tamaño de la fuente */
        }

        .tabla-optimizada th,
        .tabla-optimizada td {
            padding: 0.5rem;
            /* Reduce el padding de las celdas */
        }

        .btn-sm {
            font-size: 0.85rem;
        }

        .input-busqueda {
            max-width: 300px;
        }

        .table-success {
            background-color: #d4edda !important;
            /* Verde claro */
        }

        /* ✅ Nueva clase para cambiar color cuando está controlado */
        .fila-controlada td {
            background-color: #c3e6cb !important;
            /* Forzamos el color de fondo en cada celda */
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>
    <div id="app">
        <div class="container-fluid mt-2">

            <h2>Dietas de Internados</h2>

            <input type="text" v-model="filtro" class="form-control mb-3" placeholder="Buscar por DNI o Apellido o Sector">
            <div class="d-flex align-items-center gap-2">
                <button @click="abrirModalComida" class="btn btn-primary btn-sm d-flex align-items-center">
                    <i class="bi bi-printer me-2"></i> Etiquetas
                </button>

                <button @click="procesarColacion" class="btn btn-primary btn-sm d-flex align-items-center">
                    <i class="bi bi-printer me-2"></i> Desayuno
                </button>

                <button @click="procesarSuplemento" class="btn btn-primary btn-sm d-flex align-items-center">
                    <i class="bi bi-printer me-2"></i> Merienda
                </button>
                <button @click="procesarNocturno" class="btn btn-primary btn-sm d-flex align-items-center">
                    <i class="bi bi-printer me-2"></i> Col. Nocturna
                </button>

                <button class="btn btn-primary btn-sm d-flex align-items-center" @click="nutricionPDF">
                    <i class="bi bi-printer me-2"></i> Nutricionista PDF
                </button>
                <button class="btn btn-primary btn-sm d-flex align-items-center" @click="camareroPDF">
                    <i class="bi bi-printer me-2"></i> Camarero PDF
                </button>
                <div class="d-flex align-items-center gap-2">
                    <button @click="destildarTodo" class="btn btn-danger btn-sm d-flex align-items-center">
                        <i class="bi bi-x-circle me-2"></i> Destildar Todo
                    </button>
                </div>
            </div>
            <table class="table table-bordered table-sm tabla-optimizada">
                <thead>
                    <tr>
                        <th>Sector</th>
                        <th>Cama</th>
                        <th>DNI</th>
                        <th>Paciente</th>
                        <th>Edad</th>
                        <th>Ac.</th>
                        <th>Código</th>
                        <th>Dieta</th>
                        <th>Desayuno</th>
                        <th>Merienda</th>
                        <th>Col. Nocturna</th>
                        <th>Diagnóstico</th>
                        <th>Observación</th>
                        <th>Control</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    <tr v-for="dieta in pacientesFiltrados" :key="dieta.id" :class="{'fila-controlada': dieta.controlado == 1}">
                        <td>{{ dieta.nombre_sector || '-' }}</td>
                        <td>{{ dieta.cama || '-' }}</td>
                        <td>{{ dieta.dni || '-' }}</td>
                        <td>{{ dieta.apellido_paciente }}, {{ dieta.nombre_paciente }}</td>
                        <td>{{ dieta.edad || '-' }}</td>
                        <td>{{ dieta.acompaniante === 1 ? 'SI' : dieta.acompaniante === 0 ? 'NO' : '-' }}</td>
                        <td>{{ dieta.codigo_dieta || '-' }}</td>
                        <td>{{ dieta.nombre_dieta || '-' }}</td>
                        <td>{{ dieta.nombre_colacion || '-' }}</td>
                        <td>{{ dieta.nombre_suplemento || '-' }}</td>
                        <td>{{ dieta.nombre_nocturno || '-' }}</td>
                        <td>{{ dieta.diagnostico || '-' }}</td>
                        <td>{{ dieta.observacion || '-' }}</td>
                   
                        <td>
                            <!-- Usamos :checked para que se refleje el valor -->
                            <input type="checkbox" :checked="dieta.controlado == 1" @change="actualizarControlado(dieta)">
                        </td>

                        <td class="d-flex gap-1">
                            <button class="btn btn-info btn-sm" @click="editarDieta(dieta.id)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-outline-success btn-sm" @click="verDietas(dieta.internacion_id)">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-secondary btn-sm" @click="seleccionarUnaComida(dieta.internacion_id)">
                                <i class="bi bi-printer"></i>
                            </button>
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
                            <button @click="procesarTodoConsumo" class="btn btn-primary w-100 mt-2">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- una comida -->

            <div class="modal fade" id="modalUnaComida" tabindex="-1" aria-labelledby="modalComidaLabel" aria-hidden="true" role="dialog">
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
                            <button @click="procesarUnConsumo" class="btn btn-primary w-100 mt-2">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>

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
    <script src="./cdn/vue.global.js"></script>
    <script src="./cdn/axios.min.js"></script>
    <script src="./cdn/sweetalert2@11.js"></script>
    <script src="./cdn/jspdf.umd.min.js"></script>
    <script src="./cdn/jspdf.plugin.autotable.min.js"></script>
    <script src="./cdn/popper.min.js"></script>
    <script src="./js/pacientes_dietas.js"></script>
</body>

</html>