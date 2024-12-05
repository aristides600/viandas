<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cambios de Dietas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <div id="app" class="container mt-5">
        <h2 class="text-center mb-4">Cambios de Dietas</h2>

        <div v-if="cambiosDietas.length === 0" class="alert alert-warning text-center">
            No hay registros de cambios de dietas para esta internación.
        </div>

        <table v-else class="table table-striped table-bordered">
            <thead class="table-blue">
                <tr>
                    <th>Sector</th>
                    <th>Dieta Anterior</th>
                    <th>Dieta Nueva</th>
                    <th>Fecha de Cambio</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(cambio, index) in cambiosDietas" :key="index">
                    <td>{{ cambio.sector }}</td>
                    <td>{{ cambio.dieta_anterior }}</td>
                    <td>{{ cambio.dieta_nueva }}</td>
                    <td>{{ cambio.fecha_cambio }}</td>
                    <td>{{ cambio.usuario }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="./js/dietas_consumidas.js"></script>
</body>

</html>