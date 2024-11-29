<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Dietas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div id="app" class="container mt-5">
        <h2>Gestión de Dietas</h2>

        <!-- Tarjeta de datos del paciente -->
        <div class="row mb-4">
            <!-- Tarjeta de Datos del Paciente -->
            <div v-if="paciente && internacion" class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Datos del Paciente</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            <strong>Nombre:</strong> {{ paciente.nombre }} {{ paciente.apellido }}<br>
                            <strong>DNI:</strong> {{ paciente.dni }}<br>
                            <strong>Fecha de Nacimiento:</strong> {{ paciente.fecha_nacimiento }}<br>
                            <strong>Diagnóstico:</strong> {{ internacion.diagnostico }}<br>
                            <strong>Fecha de Ingreso:</strong> {{ internacion.fecha_ingreso }}<br>
                            <strong>Fecha de Egreso:</strong> {{ internacion.fecha_egreso ? internacion.fecha_egreso : '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cambiar Dieta -->
        <form @submit.prevent="guardarDieta">
            <div class="mb-3">
                <label for="dieta_id" class="form-label">Tipo de Dieta</label>
                <select class="form-select" v-model="dietaInternacion.dieta_id" required>
                    <option v-for="dieta in dietas" :value="dieta.id">{{ dieta.nombre }}</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="acompaniante" class="form-label">Acompañante</label>
                <input type="checkbox" v-model="dietaInternacion.acompaniante">
            </div>
            <div class="mb-3">
                <label for="postre_id" class="form-label">Postre</label>
                <select class="form-select" v-model="dietaInternacion.postre_id">
                    <option v-for="postre in postres" :value="postre.id">{{ postre.nombre }}</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="observacion" class="form-label">Observación</label>
                <textarea class="form-control" v-model="dietaInternacion.observacion" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Dieta</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="./js/dieta_internacion.js"></script>
</body>

</html>
