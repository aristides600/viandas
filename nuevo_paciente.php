<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>

    <?php include 'header.php'; ?>
    <div id="app" class="container mt-2">
        <h2>Nuevo Paciente</h2>
        <form @submit.prevent="guardarPaciente">
            <div class="mb-3">
                <label for="dni" class="form-label">DNI</label>
                <input
                    type="text"
                    class="form-control"
                    v-model="paciente.dni"
                    maxlength="8"
                    required
                    @input="paciente.dni = paciente.dni.replace(/[^0-9]/g, '').slice(0, 8)">
            </div>
            <div class=" mb-3">
                <label for="apellido" class="form-label">Apellidos</label>
                <input type="text" id="apellido" v-model="paciente.apellido" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombres</label>
                <input type="text" id="nombre" v-model="paciente.nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" id="fecha_nacimiento" v-model="paciente.fecha_nacimiento" class="form-control">
            </div>
            <div class="mb-3">
                <label for="sexo_id" class="form-label">Sexo</label>
                <select id="sexo_id" v-model="paciente.sexo_id" class="form-control" required>
                    <option value="" disabled>Seleccione Sexo</option>
                    <option v-for="sexo in sexos" :key="sexo.id" :value="sexo.id">
                        {{ sexo.nombre }}
                    </option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>


    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="./cdn/vue.global.js"></script>
    <script src="./cdn/axios.min.js"></script>
    <script src="./cdn/sweetalert2@11.js"></script>
    <script src="./cdn/jspdf.umd.min.js"></script>

    <script src="./js/nuevo_paciente.js"></script>

</body>

</html>