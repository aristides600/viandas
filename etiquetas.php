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

        <!-- Botón para generar el PDF de todas las etiquetas -->
        <button class="btn btn-primary mb-3" @click="generarPDF">Generar PDF de todas las etiquetas</button>

        <!-- Campo de búsqueda por DNI o Apellido -->
        <input type="text" v-model="filtro" class="form-control mb-3" placeholder="Buscar por DNI o Apellido">

        <!-- Tabla de pacientes -->
        <!-- Tabla de pacientes -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>DNI</th>
                    <th>Dieta</th>
                    <th>Sector</th>
                    <th>Observación</th>
                    <th>Acompañante</th> <!-- Nueva columna para acompañante -->
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="paciente in pacientesFiltrados" :key="paciente.id">
                    <td>{{ paciente.nombre_paciente }}</td>
                    <td>{{ paciente.apellido_paciente }}</td>
                    <td>{{ paciente.dni }}</td>
                    <td>{{ paciente.nombre_dieta }} ({{ paciente.codigo_dieta }})</td>
                    <td>{{ paciente.nombre_sector }}</td>
                    <td>{{ paciente.observacion || 'Sin observaciones' }}</td>
                    <td>{{ paciente.acompaniante == 1 ? 'Sí' : '' }}</td> <!-- Condición para mostrar 'Sí' o vacío -->
                    <td>
                        <button class="btn btn-secondary" @click="generarEtiquetaIndividual(paciente)">
                            Imprimir etiquetas
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>


        <div v-if="pacientesFiltrados.length === 0">
            <p>No se encontraron resultados para la búsqueda.</p>
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