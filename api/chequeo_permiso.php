<?php
require_once 'permisos.php';
session_start();

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$modulo = $input['modulo'] ?? null;

$data = ['permiso' => false];

if ($modulo && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $data['permiso'] = tienePermiso($user_id, $modulo);
}

echo json_encode($data);
?>
