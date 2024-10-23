<?php
// db.php: Conexión a la base de datos
include 'db.php';

header('Content-Type: application/json');

try {
    $query = "SELECT i.id AS internacion_id, p.apellido, p.nombre, d.codigo AS dieta_codigo, pd.observacion, 
                     c.nombre AS comida_nombre, pd.fecha_consumo, s.nombre AS sector_nombre
              FROM internaciones i
              JOIN pacientes p ON i.paciente_id = p.id
              JOIN sectores s ON i.sector_id = s.id
              JOIN pacientes_dietas pd ON pd.internacion_id = i.id
              JOIN dietas d ON pd.dieta_id = d.id
              JOIN comidas c ON pd.comida_id = c.id
              WHERE i.fecha_egreso IS NULL
              ORDER BY s.nombre, p.apellido, p.nombre";
              
    $result = $conn->query($query);
    $dietas = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode(['status' => 'success', 'data' => $dietas]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
