<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Internaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div id="app" class="container mt-5">
        <h1 class="mb-4">Registro de Internaciones</h1>

        <!-- Formulario de Internación -->
        <form @submit.prevent="registrarInternacion">
            <div class="mb-3">
                <label for="paciente" class="form-label">Paciente</label>
                <select v-model="internacion.paciente_id" class="form-select" id="paciente" required>
                    <option v-for="paciente in pacientes" :key="paciente.id" :value="paciente.id">
                        {{ paciente.nombre }} {{ paciente.apellido }}
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                <input type="date" v-model="internacion.fecha_ingreso" class="form-control" id="fecha_ingreso" required>
            </div>

            <div class="mb-3">
                <label for="fecha_egreso" class="form-label">Fecha de Egreso</label>
                <input type="date" v-model="internacion.fecha_egreso" class="form-control" id="fecha_egreso">
            </div>

            <div class="mb-3">
                <label for="dieta" class="form-label">Dieta</label>
                <select v-model="internacion.dieta_id" class="form-select" id="dieta" required>
                    <option v-for="dieta in dietas" :key="dieta.id" :value="dieta.id">
                        {{ dieta.descripcion }}
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label for="profesional" class="form-label">Profesional</label>
                <select v-model="internacion.profesional_id" class="form-select" id="profesional" required>
                    <option v-for="profesional in profesionales" :key="profesional.id" :value="profesional.id">
                        {{ profesional.nombre }} {{ profesional.apellido }}
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label for="sector" class="form-label">Sector</label>
                <select v-model="internacion.sector_id" class="form-select" id="sector" required>
                    <option v-for="sector in sectores" :key="sector.id" :value="sector.id">
                        {{ sector.nombre }}
                    </option>
                </select>
            </div>
            <div class="mb-3">
                <label for="diagnostico" class="form-label">Diagnostico</label>
                <textarea v-model="internacion.diagnostico" class="form-control" id="diagnostico" rows="2"></textarea>
            </div>

            <div class="mb-3">
                <label for="observacion" class="form-label">Observación</label>
                <textarea v-model="internacion.observacion" class="form-control" id="observacion" rows="2"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Registrar Internación</button>
        </form>

        <!-- Mensaje de Éxito -->
        <div v-if="mensaje" class="alert alert-success mt-3">{{ mensaje }}</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="./js/internaciones.js"></script>
</body>

</html>