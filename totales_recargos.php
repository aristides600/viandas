<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Comidas por Sector</title>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .meal-table {
            margin-bottom: 20px;
            width: 100%;
            border-collapse: collapse;
        }

        .meal-table th,
        .meal-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .meal-table th {
            background-color: #f2f2f2;
        }

        .meal-table caption {
            font-weight: bold;
            padding: 8px;
            text-align: left;
        }

        .total-row {
            font-weight: bold;
        }

        @media (max-width: 600px) {
            .meal-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>
    <div id="app" class="container">
        <h1>Recargos</h1>
        <div v-if="loading">Cargando datos...</div>
        <div v-else-if="error">Error al cargar los datos: {{ error }}</div>
        <div v-else>
            <table class="meal-table" v-if="almuerzos.length > 0">
                <caption>Almuerzos</caption>
                <thead>
                    <tr>
                        <th>Sector</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="almuerzo in almuerzos" :key="'almuerzo-' + almuerzo.sector">
                        <td>{{ almuerzo.sector }}</td>
                        <td>{{ almuerzo.total_cantidad }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>Total</td>
                        <td>{{ totalAlmuerzos }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="meal-table" v-if="cenas.length > 0">
                <caption>Cenas</caption>
                <thead>
                    <tr>
                        <th>Sector</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="cena in cenas" :key="'cena-' + cena.sector">
                        <td>{{ cena.sector }}</td>
                        <td>{{ cena.total_cantidad }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>Total</td>
                        <td>{{ totalCenas }}</td>
                    </tr>
                </tbody>
            </table>

            <p v-else>No hay servicios registrados para hoy.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="./js/totales_recargos.js"></script>

</body>

</html>