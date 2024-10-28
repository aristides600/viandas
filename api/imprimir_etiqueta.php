<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $zpl = $_POST['zpl'];

    // Crear un archivo temporal con el contenido ZPL
    $tempFile = tempnam(sys_get_temp_dir(), 'zebra') . '.txt';
    file_put_contents($tempFile, $zpl);

    // Ejecutar el comando print para enviar a la impresora predeterminada
    $command = 'print /D:"%PRINTER%" ' . escapeshellarg($tempFile);
    shell_exec($command);

    // Eliminar el archivo temporal después de enviarlo
    unlink($tempFile);

    http_response_code(200);
    echo json_encode(array("message" => "Etiqueta enviada a la impresora predeterminada"));
} else {
    http_response_code(405); // Método no permitido
    echo json_encode(array("error" => "Método no permitido")); // Usando array()
}
?>
