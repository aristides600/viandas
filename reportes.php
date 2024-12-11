<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumos Diarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>

    <div id="app" class="container mt-5">
        <h2>Consumo de Dietas por Sector</h2>

        <div class="mb-3">
            <label for="fecha_desde" class="form-label">Fecha Desde</label>
            <input type="date" id="fecha_desde" class="form-control" v-model="fechaDesde">
        </div>

        <div class="mb-3">
            <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
            <input type="date" id="fecha_hasta" class="form-control" v-model="fechaHasta" :max="maxDate">
        </div>

        <button class="btn btn-primary" @click="getConsumos">Cargar Consumos</button>

        <table class="table mt-4">
            <thead>
                <tr>
                    <th>Sector</th>
                    <th>Cantidad de Dietas</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(consumo, index) in consumos" :key="index">
                    <td>{{ consumo.sector }}</td>
                    <td>{{ consumo.cantidad }}</td>
                </tr>
            </tbody>
        </table>

        <canvas id="myChart" width="400" height="200"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="./js/reportes.js"></script>
</body>

</html>