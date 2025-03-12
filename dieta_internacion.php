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
    <div id="app" class="container mt-2">
        <h2>Asignar Dieta</h2>

        <div class="row mb-4">
            <!-- Tarjeta de Datos del Paciente -->
            <div v-if="paciente && internacion" class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Datos del Paciente</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            <strong>Paciente:</strong> {{ paciente.apellido }}, {{ paciente.nombre }}<br>
                            <strong>DNI:</strong> {{ paciente.dni }}<br>
                            <strong>Fecha de Nacimiento:</strong> {{ formatoFecha(paciente.fecha_nacimiento) }}<br>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Datos de la Internación -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="card-title mb-0">Datos de la Internación</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            <strong>Diagnóstico:</strong> {{ internacion.diagnostico }}<br>
                            <strong>Fecha de Ingreso:</strong> {{ formatoFecha(internacion.fecha_ingreso) }}<br>
                            <strong>Fecha de Egreso:</strong> {{ internacion.fecha_egreso || 'Sin egreso registrado' }}<br>
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
                    <option disabled value="">Seleccionar una dieta</option>
                    <option v-for="dieta in dietas" :value="dieta.id">{{ dieta.codigo }} - {{ dieta.nombre }}</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="acompaniante" class="form-label">Acompañante</label>
                <input type="checkbox" v-model="dietaInternacion.acompaniante">
            </div>
            <div class="mb-3">
                <label for="postre_id" class="form-label">Postre</label>
                <select class="form-select" v-model="dietaInternacion.postre_id" required>
                    <option disabled value="">Seleccionar un postre</option>
                    <option v-for="postre in postres" :value="postre.id">{{ postre.nombre }}</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="colacion_id" class="form-label">Colación</label>
                <select class="form-select" v-model="dietaInternacion.colacion_id" required>
                    <option disabled value="">Seleccionar una colación</option>
                    <option v-for="colacion in colaciones" :value="colacion.id">{{ colacion.nombre }}</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="suplemento_id" class="form-label">Suplemento</label>
                <select class="form-select" v-model="dietaInternacion.suplemento_id" required>
                    <option disabled value="">Seleccionar un suplemento</option>
                    <option v-for="suplemento in suplementos" :value="suplemento.id">{{ suplemento.nombre }}</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="mensaje" class="form-label">Mensaje</label>
                <input
                    type="text"
                    class="form-control"
                    v-model="dietaInternacion.mensaje"
                    maxlength="50">
            </div>

            <div class="mb-3">
                <label for="observacion" class="form-label">Observación</label>
                <input
                    type="text"
                    class="form-control"
                    v-model="dietaInternacion.observacion"
                    maxlength="50">
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