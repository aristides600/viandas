<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir Etiquetas de Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div id="app" class="container mt-5">
        <h2>Etiquetas de Pacientes Internados</h2>
        <button class="btn btn-primary mb-3" @click="generarPDF">Generar PDF</button>

        <div v-if="pacientes.length > 0">
            <div v-for="paciente in pacientes" class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ paciente.nombre_paciente }} {{ paciente.apellido_paciente }}</h5>
                    <p class="card-text">
                        <strong>Dieta:</strong> {{ paciente.nombre_dieta }} ({{ paciente.codigo_dieta }})<br>
                        <strong>Sector:</strong> {{ paciente.nombre_sector }}<br>
                        <strong>Observación:</strong> {{ paciente.observacion }}
                    </p>
                </div>
            </div>
        </div>
        <div v-else>
            <p>No hay pacientes internados sin fecha de egreso.</p>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="./js/etiquetas.js"></script>


</body>

</html>