<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Etiqueta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div id="app" class="container mt-5">
        <h1 class="mb-4">Generar Etiqueta para Impresión</h1>

        <!-- Formulario para Seleccionar Sector y Fechas -->
        <form @submit.prevent="buscarInternaciones">
            <div class="mb-3">
                <label for="sector" class="form-label">Sector</label>
                <select v-model="filtro.sector_id" class="form-select" id="sector" required>
                    <option value="">Seleccione un sector</option>
                    <option v-for="sector in sectores" :key="sector.id" :value="sector.id">
                        {{ sector.nombre }}
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label for="fecha_desde" class="form-label">Fecha Desde</label>
                <input type="date" v-model="filtro.fecha_desde" class="form-control" id="fecha_desde" required>
            </div>

            <div class="mb-3">
                <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                <input type="date" v-model="filtro.fecha_hasta" class="form-control" id="fecha_hasta">
            </div>

            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

        <!-- Botón para Imprimir Todas las Etiquetas -->
        <button v-if="internaciones.length > 0" class="btn btn-secondary mt-3" @click="imprimirTodasEtiquetas">
            Imprimir Todas las Etiquetas
        </button>

        <!-- Mostrar los resultados en una tabla -->
        <div v-if="internaciones.length > 0" class="mt-4">
            <h2>Resultados</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Dieta</th>
                        <th>Profesional</th>
                        <th>Observación</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="internacion in internaciones" :key="internacion.id">
                        <td>{{ internacion.paciente_nombre }} {{ internacion.paciente_apellido }}</td>
                        <td>{{ internacion.dieta_descripcion }}</td>
                        <td>{{ internacion.profesional_nombre }} {{ internacion.profesional_apellido }}</td>
                        <td>{{ internacion.observacion }}</td>
                        <td>
                            <button class="btn btn-success" @click="imprimirEtiqueta(internacion)">Imprimir Etiqueta</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/zebra-browser-print"></script>
    <script src="./js/etiquetas.js"></script>

</body>

</html>