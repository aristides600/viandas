<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Recargos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <div id="app" class="container mt-2">
        <h3 class="text-center mb-2">Recargos</h3>

        <form @submit.prevent="guardarRecargo" class="d-flex flex-wrap gap-1 align-items-end">
            <div class="flex-grow-1" style="min-width: 200px;">
                <label class="form-label mb-1">Nombre</label>
                <input v-model="recargo.nombre" class="form-control" required>
            </div>

            <div class="flex-grow-1" style="min-width: 200px;">
                <label class="form-label mb-1">Sector</label>
                <input v-model="recargo.sector" class="form-control" required>
            </div>

            <div class="flex-grow-1" style="min-width: 150px;">
                <label class="form-label mb-1">Comida</label>
                <select v-model="recargo.comida_id" class="form-select" required>
                    <option value="" disabled>Seleccionar comida</option>
                    <option v-for="comida in comidas" :value="comida.id">{{ comida.nombre }}</option>
                </select>
            </div>

            <div class="flex-grow-1" style="min-width: 200px;">
                <label class="form-label mb-1">Observacion</label>
                <input v-model="recargo.observacion" class="form-control">
            </div>
            <div class="flex-grow-1" style="min-width: 90px;">
                <input v-model="recargo.cantidad" type="number" class="form-control" required min="0" max="99" @input="validarCantidad">

            </div>

            <div>
                <button type="submit" class="btn btn-success mt-2 mt-md-0">
                    <i class="bi bi-save me-1"></i>Guardar
                </button>
            </div>
        </form>

        <!-- Botón Recargos y campo Buscar en línea -->
        <div class="d-flex align-items-center mt-2 mb-2">
            <div style="min-width: 140px; margin-right: 10px;">
                <button @click="abrirModalComida" class="btn btn-primary btn-sm w-100 d-flex align-items-center justify-content-center">
                    <i class="bi bi-printer me-2"></i> Recargos
                </button>
            </div>

            <input type="text" v-model="filtro" class="form-control" placeholder="Buscar por Nombre, Sector o Comida">
        </div>

        <div class="table-responsive mt-2">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Sector</th>
                        <th>Comida</th>
                        <th>Observacion</th>
                        <th>Cantidad</th>
                        <th>Control</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- <tr v-for="item in recargosFiltrados" :key="item.id"> -->
                    <tr v-for="item in recargosFiltrados" :key="item.id" :class="{ 'table-primary': item.controlado == 1 }">


                        <td>{{ item.nombre }}</td>
                        <td>{{ item.sector }}</td>
                        <td>{{ item.comida_nombre || 'Sin nombre' }}</td>
                        <td>{{ item.observacion }}</td>

                        <td>
                            <input type="number" class="form-control form-control-sm" :value="item.cantidad"
                                @change="actualizarCantidad(item.id, $event.target.value)">
                        </td>
                        <td>
                            <input type="checkbox" :checked="item.controlado == 1" @change="actualizarControlado(item)">

                        </td>

                        <td>
                            <button class="btn btn-warning btn-sm me-2" @click="editarRecargo(item)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <!-- <button class="btn btn-danger btn-sm" @click="eliminarRecargo(item.id)">
                                <i class="bi bi-trash"></i>
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
                            <button @click="procesarTodoRecargo" class="btn btn-primary w-100 mt-2">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php include 'footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>

    <!-- Add Bootstrap JS here -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.15/jspdf.plugin.autotable.min.js"></script>

    <script src="./js/recargos.js"></script>

</body>

</html>