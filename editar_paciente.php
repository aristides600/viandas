<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paciente</title>
    <link href="./cdn/bootstrap.min.css" rel="stylesheet">
    <link href="./cdn/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>
    <?php include 'header.php'; ?>
    <div id="app" class="container mt-5">
        <h2>Editar Paciente</h2>
        <form @submit.prevent="editarPaciente">
            <div class="mb-3">
                <label for="dni" class="form-label">DNI</label>
                <input type="number" id="dni" v-model="paciente.dni" class="form-control" required>
            </div>
            <div class="mb-3">
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
                <select id="sexo_id" v-model="paciente.sexo_id" class="form-control">
                    <option value="" disabled>Seleccione Sexo</option>
                    <option v-for="sexo in sexos" :key="sexo.id" :value="sexo.id">
                        {{ sexo.nombre }}
                    </option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
    <script src="./cdn/vue.global.js"></script>
    <script src="./cdn/axios.min.js"></script>
    <script src="./cdn/sweetalert2@10.js"></script>
    <script src="./js/editar_paciente.js"></script>

    <?php include 'footer.php'; ?>
</body>

</html>
