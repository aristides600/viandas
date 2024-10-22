<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Dietas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div id="app" class="container mt-4">
    <h2>Dietas del Paciente</h2>

    <!-- Tabla para mostrar las dietas -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Dieta</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="dieta in dietas" :key="dieta.id">
                <td>{{ dieta.id }}</td>
                <td>{{ dieta.dieta_id }}</td>
                <td>{{ dieta.fecha }}</td>
                <td>
                    <button class="btn btn-danger" @click="eliminarDieta(dieta.id)">Eliminar</button>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Formulario para agregar nueva dieta -->
    <div class="mb-3">
        <label for="dieta_id" class="form-label">ID de la Dieta</label>
        <input type="text" class="form-control" id="dieta_id" v-model="nuevaDieta.dieta_id">
    </div>

    <button class="btn btn-primary" @click="agregarDieta">Agregar Dieta</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="./js/editar_internacion.js"></script>

</body>
</html>
