<?php
// Iniciar sesión
session_start();

// Destruir todas las sesiones
session_unset(); // Eliminar todas las variables de sesión
session_destroy(); // Destruir la sesión

// Redirigir al usuario a la página de inicio de sesión u otra página
header("Location: login_itv.php"); // Cambia "login.php" a la página de inicio de sesión de tu preferencia
exit();
?>
