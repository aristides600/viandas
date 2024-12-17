<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Consumos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.0/dist/vue.global.js"></script>
    
    <!-- Estilos CSS para alternar filas -->
    <style>
        /* Alternar colores de fondo entre filas */
        .table-striped tbody tr:nth-child(odd) {
            background-color: #f2f2f2; /* Gris claro para filas impares */
        }

        .table-striped tbody tr:nth-child(even) {
            background-color: #ffffff; /* Blanco para filas pares */
        }
    </style>
</head>

<body>
    <div id="app" class="container mt-5">
        <h3 class="text-center">Dietas - {{ fechaActual }}</h3>

        <div class="row">
            <!-- Almuerzo -->
            <div class="col-md-6 mb-4">
                <h4>Almuerzo</h4>
                <div v-for="(grupo, index) in almuerzo" :key="index" class="mb-4">
                    <h4>Grupo {{ index + 1 }}</h4>
                    <table class="table table-bordered table-striped"> <!-- Añadido 'table-striped' -->
                        <thead>
                            <tr>
                                <th>Sector</th>
                                <th>Postres</th>
                                <th>Gelatinas</th>
                                <th>Dietas Generales</th>
                                <th>Otras Dietas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(data, idx) in grupo" :key="idx">
                                <td>{{ data.sector }}</td>
                                <td>{{ data.total_flan }}</td>
                                <td>{{ data.total_gelatina }}</td>
                                <td>{{ data.total_dietas_generales }}</td>
                                <td>{{ data.total_otras_dietas }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <td>{{ calcularTotales(grupo).totalFlan }}</td>
                                <td>{{ calcularTotales(grupo).totalGelatina }}</td>
                                <td>{{ calcularTotales(grupo).totalDietasGenerales }}</td>
                                <td>{{ calcularTotales(grupo).totalOtrasDietas }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Cena -->
            <div class="col-md-6 mb-4">
                <h4>Cena</h4>
                <div v-for="(grupo, index) in cena" :key="index" class="mb-4">
                    <h4>Grupo {{ index + 1 }}</h4>
                    <table class="table table-bordered table-striped"> <!-- Añadido 'table-striped' -->
                        <thead>
                            <tr>
                                <th>Sector</th>
                                <th>Postres</th>
                                <th>Gelatinas</th>
                                <th>Dietas Generales</th>
                                <th>Otras Dietas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(data, idx) in grupo" :key="idx">
                                <td>{{ data.sector }}</td>
                                <td>{{ data.total_flan }}</td>
                                <td>{{ data.total_gelatina }}</td>
                                <td>{{ data.total_dietas_generales }}</td>
                                <td>{{ data.total_otras_dietas }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <td>{{ calcularTotales(grupo).totalFlan }}</td>
                                <td>{{ calcularTotales(grupo).totalGelatina }}</td>
                                <td>{{ calcularTotales(grupo).totalDietasGenerales }}</td>
                                <td>{{ calcularTotales(grupo).totalOtrasDietas }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Cargar Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Cargar Vue.js -->
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <!-- Cargar el archivo JS -->
    <script src="./js/totales.js"></script>

</body>

</html>
