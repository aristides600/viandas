<?php
session_start();
session_destroy();
// Redirigir a la página de login
header("Location: login.php");
exit();
?>
