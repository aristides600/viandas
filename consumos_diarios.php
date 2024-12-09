<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Comida</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/estilos.css">
</head>
<body>
<?php include 'header.php'; ?>

    <div id="app" class="container mt-5">
        <h1 class="text-center">Seleccionar Comida</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form @submit.prevent="consumoDiario" method="POST">
                    <div class="mb-3">
                        <label for="comida_id" class="form-label">Comida</label>
                        <select id="comida_id" v-model="comidaSeleccionada" class="form-control" required>
                            <option value="" disabled>Seleccione Comida</option>
                            <option v-for="comida in comidas" :key="comida.id" :value="comida.id">
                                {{ comida.nombre }}
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Registrar Consumo</button>
                </form>
                <div v-if="mensaje" class="alert mt-3" :class="mensaje.clase">
                    {{ mensaje.texto }}
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.31/dist/vue.global.prod.js"></script>
    <script src="./js/consumos_diarios.js"></script>
</body>
</html>
