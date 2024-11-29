<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Pacientes Dietas</title>
  <link href="./cdn/bootstrap.min.css" rel="stylesheet">
  <link href="./cdn/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>
  <?php include 'header.php'; ?>
  <div id="app" class="container mt-5">
    <h2>Editar Paciente Dieta</h2>
    <form @submit.prevent="guardarCambios">
      <div class="row">
        <div class="col-md-6">
          <div class="card h-100">
            <div class="card-header bg-primary text-white">
              <h5 class="card-title mb-0">Datos del Paciente</h5>
            </div>
            <div class="card-body">
              <p class="card-text">
                <strong>Nombre:</strong> {{ paciente.nombre }} {{ paciente.apellido }}<br>
                <strong>DNI:</strong> {{ paciente.dni }}<br>
                <strong>Fecha de Nacimiento:</strong> {{ paciente.fecha_nacimiento }}<br>
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card h-100">
            <div class="card-header bg-secondary text-white">
              <h5 class="card-title mb-0">Datos de la Internación</h5>
            </div>
            <div class="card-body">
              <p class="card-text">
                <strong>Diagnóstico:</strong> {{ internacion.diagnostico }}<br>
                <strong>Fecha de Ingreso:</strong> {{ internacion.fecha_ingreso }}<br>
                <strong>Fecha de Egreso:</strong> {{ internacion.fecha_egreso ? internacion.fecha_egreso : 'Sin egreso registrado' }}<br>
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="mb-3">
        <label for="dieta_id" class="form-label">Dieta</label>
        <select v-model="pacienteDieta.dieta_id" class="form-control" id="dieta_id" required>
          <option v-for="dieta in dietas" :key="dieta.id" :value="dieta.id">{{ dieta.nombre }}</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="fecha_consumo" class="form-label">Fecha de Consumo</label>
        <input type="date" v-model="pacienteDieta.fecha_consumo" class="form-control" id="fecha_consumo" required>
      </div>
      <div class="mb-3">
        <label for="acompaniante" class="form-label">¿Acompañante?</label>
        <input type="checkbox" v-model="pacienteDieta.acompaniante" id="acompaniante">
      </div>
      <div class="mb-3">
        <label for="observacion" class="form-label">Observación</label>
        <textarea v-model="pacienteDieta.observacion" class="form-control" id="observacion" rows="3"></textarea>
      </div>
      <div class="mb-3">
        <label for="postre_id" class="form-label">Postre</label>
        <select v-model="pacienteDieta.postre_id" class="form-control" id="postre_id">
          <option v-for="postre in postres" :key="postre.id" :value="postre.id">{{ postre.nombre }}</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
  </div>
  <script src="./cdn/vue.global.js"></script>
  <script src="./cdn/axios.min.js"></script>
  <script src="./cdn/sweetalert2@10.js"></script>
  <script src="./js/editar_dieta.js"></script>
  <?php include 'footer.php'; ?>
</body>

</html>