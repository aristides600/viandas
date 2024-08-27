<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Internaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div id="app" class="container mt-5">
        <h1 class="mb-4">Informe de Internaciones</h1>

        <!-- Filtros de fechas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <label for="fechaDesde" class="form-label">Fecha Desde:</label>
                <input type="date" v-model="fechaDesde" class="form-control" id="fechaDesde">
            </div>
            <div class="col-md-3">
                <label for="fechaHasta" class="form-label">Fecha Hasta:</label>
                <input type="date" v-model="fechaHasta" class="form-control" id="fechaHasta">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button @click="generarReporte" class="btn btn-primary">Generar Informe</button>
            </div>
        </div>

        <!-- Tabla de informe -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sector</th>
                    <th>Dieta</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(dieta, index) in reporte" :key="index">
                    <td>{{ dieta.sector }}</td>
                    <td>{{ dieta.dieta }}</td>
                    <td>{{ dieta.cantidad }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Subtotales y Total general -->
        <h5 class="mt-4">Subtotales y Total General</h5>
        <div v-for="(subtotal, index) in subtotales" :key="index">
            <strong>{{ subtotal.sector }}:</strong> {{ subtotal.total }} dietas
        </div>
        <div class="mt-2">
            <strong>Total General:</strong> {{ totalGeneral }} dietas
        </div>

        <!-- Gráfico -->
        <h5 class="mt-4">Gráfico de Dietas por Sector</h5>
        <canvas id="dietaChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./js/reportes.js"></script>
</body>

</html>
