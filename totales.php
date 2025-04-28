<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Consumos</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="./css/estilos.css">

    <!-- Estilos locales -->
    <style>
        .almuerzo {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .cena {
            background-color: #cce5ff;
            border: 1px solid #b8daff;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        .table-striped tbody tr:nth-child(even) {
            background-color: #ffffff;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <?php include 'header.php'; ?>

    <div id="app" class="container mt-1">
        <h5 class="text-center mb-4">Dietas - {{ fechaActual }}</h5>
        <div class="row">

            <!-- Bloque de Almuerzo -->
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h5>Almuerzo</h5>
                    </div>
                    <div class="card-body">
                        <div v-if="almuerzo.length > 0">
                            <!-- Tabla de Almuerzo -->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Sector</th>
                                        <th>Dietas Generales</th>
                                        <th>Otras Dietas</th>
                                        <th>Gelatinas</th>
                                        <th>Otros Postres</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="sector in almuerzo" :key="sector.sector_id">
                                        <td>{{ sector.sector_nombre }}</td>
                                        <td>{{ sector.total_dietas_generales }}</td>
                                        <td>{{ sector.total_otros_dietas }}</td>
                                        <td>{{ sector.total_gelatinas }}</td>
                                        <td>{{ sector.total_otros_postres }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td><strong>Totales</strong></td>
                                        <td><strong>{{ totalAlmuerzo.dietasGenerales }}</strong></td>
                                        <td><strong>{{ totalAlmuerzo.otrasDietas }}</strong></td>
                                        <td><strong>{{ totalAlmuerzo.gelatinas }}</strong></td>
                                        <td><strong>{{ totalAlmuerzo.otrosPostres }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div v-else class="text-center text-muted">
                            No hay datos para almuerzo.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bloque de Cena -->
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white text-center">
                        <h5>Cena</h5>
                    </div>
                    <div class="card-body">
                        <div v-if="cena.length > 0">
                            <!-- Tabla de Cena -->
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Sector</th>
                                        <th>Dietas Generales</th>
                                        <th>Otras Dietas</th>
                                        <th>Gelatinas</th>
                                        <th>Otros Postres</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="sector in cena" :key="sector.sector_id">
                                        <td>{{ sector.sector_nombre }}</td>
                                        <td>{{ sector.total_dietas_generales }}</td>
                                        <td>{{ sector.total_otros_dietas }}</td>
                                        <td>{{ sector.total_gelatinas }}</td>
                                        <td>{{ sector.total_otros_postres }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td><strong>Totales</strong></td>
                                        <td><strong>{{ totalCena.dietasGenerales }}</strong></td>
                                        <td><strong>{{ totalCena.otrasDietas }}</strong></td>
                                        <td><strong>{{ totalCena.gelatinas }}</strong></td>
                                        <td><strong>{{ totalCena.otrosPostres }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div v-else class="text-center text-muted">
                            No hay datos para cena.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="./js/totales.js"></script>

</body>

</html>