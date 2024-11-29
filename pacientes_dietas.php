<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Dietas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div id="app">
        <div class="container mt-5">
            <!-- Campo de búsqueda por DNI o Apellido -->
            <input type="text" v-model="filtro" class="form-control mb-3" placeholder="Buscar por DNI o Apellido">
            <h2>Dietas por Sector</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sector</th>
                        <th>Apellido</th>
                        <th>Nombre</th>
                        <th>Edad</th>
                        <th>Código de Dieta</th> <!-- Nueva columna para el código de la dieta -->
                        <th>Observación</th>
                        <th>Comida</th>
                        <th>Fecha Consumo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="dieta in pacientesFiltrados" :key="dieta.id">
                        <td>{{ dieta.nombre_sector }}</td>
                        <td>{{ dieta.apellido_paciente }}</td>
                        <td>{{ dieta.nombre_paciente }}</td>
                        <td>{{ dieta.edad }}</td>
                        <td>{{ dieta.codigo_dieta }}</td> <!-- Mostrar el código de la dieta -->
                        <td>{{ dieta.observacion }}</td>
                        <td>{{ dieta.nombre_comida }}</td>
                        <td>{{ dieta.fecha_consumo }}</td>
                    </tr>
                </tbody>
            </table>
            <button class="btn btn-primary mt-3" @click="generatePDF">Generar PDF</button>
        </div>
        <div v-if="pacientesFiltrados.length === 0">
            <p>No se encontraron resultados para la búsqueda.</p>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.15/jspdf.plugin.autotable.min.js"></script>
    <script src="./js/pacientes_dietas.js"></script>
</body>

</html>
