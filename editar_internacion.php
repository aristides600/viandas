<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Dieta Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>
<?php include 'header.php'; ?>
    <div id="app" class="container mt-5">
        <h2>Cargar Dieta Paciente</h2>
        <!-- Tarjeta para mostrar los datos del paciente -->
        <div v-if="paciente" class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">{{ paciente.nombre }} {{ paciente.apellido }}</h5>
                <p class="card-text">
                    <strong>DNI:</strong> {{ paciente.dni }}<br>
                    <strong>Fecha de Nacimiento:</strong> {{ paciente.fecha_nacimiento }}<br>
                    <strong>Teléfono:</strong> {{ paciente.telefono }}<br>
                    <strong>Diagnóstico:</strong> {{ internacion.diagnostico }}<br>
                    <strong>Fecha de Ingreso:</strong> {{ internacion.fecha_ingreso }}<br>
                    <strong>Fecha de Egreso:</strong> {{ internacion.fecha_egreso ? internacion.fecha_egreso : '-' }}
                </p>
            </div>
        </div>
        <form @submit.prevent="guardarDieta">
            <!-- <div class="mb-3">
                <label for="paciente_id" class="form-label">ID Paciente</label>
                <input type="number" class="form-control" v-model="form.paciente_id" required readonly>
            </div>
            <div class="mb-3">
                <label for="internacion_id" class="form-label">ID Internación</label>
                <input type="number" class="form-control" v-model="form.internacion_id" required readonly>
            </div> -->
            <div class="mb-3">
                <label for="dieta_id" class="form-label">Tipo de Dieta</label>
                <select class="form-select" v-model="form.dieta_id" required>
                    <option v-for="dieta in dietas" :value="dieta.id">{{ dieta.nombre }}</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="comida_id" class="form-label">Tipo de Comida</label>
                <select class="form-select" v-model="form.comida_id" required>
                    <option v-for="comida in comidas" :value="comida.id">{{ comida.nombre }}</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="fecha_consumo" class="form-label">Fecha de Consumo</label>
                <input type="date" class="form-control" v-model="form.fecha_consumo" required>
            </div>
            <div class="mb-3">
                <label for="observacion" class="form-label">Observación</label>
                <textarea class="form-control" v-model="form.observacion"></textarea>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" v-model="form.acompaniante">
                <label class="form-check-label" for="acompaniante">Acompañante</label>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="./js/editar_internacion.js"></script>


</body>

</html>