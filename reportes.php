<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Internaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <div id="app" class="container mt-5">
        <h2 class="mb-4">Consumos de Dietas</h2>

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

        <!-- Botón para generar PDF -->
        <div class="mt-4" v-if="reporte.length > 0">
            <button @click="generarPDF" class="btn btn-success">Generar PDF</button>
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

        <!-- Nueva Tabla con Dietas y Cantidades -->
        <h5 class="mt-4">Dietas y Cantidades</h5>
        <table>
            <thead>
                <tr>
                    <th>Dieta</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="dieta in dietasTotales" :key="dieta.dieta">
                    <td>{{ dieta.dieta }}</td>
                    <td>{{ dieta.total }}</td>
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
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://unpkg.com/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="./js/reportes.js"></script>
</body>

</html>