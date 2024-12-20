<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Internación</title>
  <link href="./cdn/bootstrap.min.css" rel="stylesheet">
  <link href="./cdn/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>
  <?php include 'header.php'; ?>
  <div id="app" class="container mt-2">
    <h2>Editar Internación</h2>
    <form @submit.prevent="editarInternacion">
      <div class="row">
        <!-- Tarjeta de Datos del Paciente -->
        <div class="col-md-6">
          <div class="card h-100">
            <div class="card-header bg-primary text-white">
              <h5 class="card-title mb-0">Datos del Paciente</h5>
            </div>
            <div class="card-body">
              <p class="card-text">
                <strong>Paciente:</strong> {{ paciente.apellido }}, {{ paciente.nombre }}<br>
                <strong>DNI:</strong> {{ paciente.dni }}<br>
                <strong>Fecha de Nacimiento:</strong> {{ paciente.fecha_nacimiento }}<br>
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
                <strong>Fecha de Ingreso:</strong> {{ internacion.fecha_ingreso }}<br>
                <strong>Fecha de Egreso:</strong> {{ internacion.fecha_egreso || 'Sin egreso registrado' }}<br>
                <strong>Sector:</strong> {{ sectorNombre }}<br>
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label for="sectorId" class="form-label">Sector</label>
        <select
          id="sectorId"
          class="form-select"
          v-model="internacion.sector_id"
          required>
          <option value="" disabled>Seleccionar Sector</option>
          <option v-for="sector in sectores" :key="sector.id" :value="sector.id">
            {{ sector.nombre }}
          </option>
        </select>
      </div>
      <!-- <div class="mb-3">
        <label for="cama" class="form-label">Cama</label>
        <input type="number" class="form-control" v-model="internacion.cama" maxlength="3" required>
      </div> -->
      <div class="mb-3">
        <label for="cama" class="form-label">Cama</label>
        <input
          type="text"
          class="form-control"
          v-model="internacion.cama"
          maxlength="3"
          required
          @input="internacion.cama = internacion.cama.replace(/[^0-9]/g, '').slice(0, 3)">
      </div>


      <!-- Campo de texto para el Diagnóstico -->
      <div class="mb-3">
        <label for="diagnostico" class="form-label">Diagnóstico</label>
        <textarea
          id="diagnostico"
          class="form-control"
          rows="4"
          v-model="internacion.diagnostico"
          required></textarea>
      </div>

      <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
  </div>
  <script src="./cdn/vue.global.js"></script>
  <script src="./cdn/axios.min.js"></script>
  <script src="./cdn/sweetalert2@10.js"></script>
  <script src="./js/editar_internacion.js"></script>
  <?php include 'footer.php'; ?>
</body>

</html>