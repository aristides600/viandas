<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayuda</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/estilos.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container mt-4">
        <h2>Ayuda</h2>
        <p>A continuación, encontrarás videos tutoriales para ayudarte a usar el sistema:</p>
        <div class="mb-4">
            <h3>Alta de Paciente</h3>
            <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/mk85nYO0TKc" title="Alta de Paciente" allowfullscreen></iframe>
            </div>
        </div>
        <div class="mb-4">
            <h3>Nueva Internacion</h3>
            <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/zOUHkvHIbxk" title="Generar una Internacion" allowfullscreen></iframe>
            </div>
        </div>

        <div class="mb-4">
            <h3>Asignar una Dieta</h3>
            <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/_dNvgL6ezlQ" title="Como Asignar una Dieta" allowfullscreen></iframe>
            </div>
        </div>

        <div class="mb-4">
            <h3>Cambiar la Dieta</h3>
            <div class="ratio ratio-16x9">
                <iframe src="https://www.youtube.com/embed/tJxP7wqfwqI" title="Como cambiar una dieta de un paciente internado" allowfullscreen></iframe>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-4">
            <a href="index.php" class="btn btn-danger ms-auto">Salir</a>
        </div>
    </div>


    <?php include 'footer.php'; ?>
</body>

</html>