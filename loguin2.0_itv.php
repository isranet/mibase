<?php
// Habilitar la visualización de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sesión
session_start();

// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Usuario predeterminado de XAMPP
$password = ""; // Contraseña vacía por defecto en XAMPP
$dbname = "mibase_itv";

// Crear conexión
$conn = new mysqli($servername, $username, $password);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Crear base de datos si no existe
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
$conn->query($sql);
$conn->select_db($dbname);

// Crear tabla de alumnos si no existe
$tableAlumno = "CREATE TABLE IF NOT EXISTS alumno_itv (
    id_alumno_itv INT AUTO_INCREMENT PRIMARY KEY,
    nombre_itv VARCHAR(100) NOT NULL,
    fecha_nacimiento_itv DATE NOT NULL,
    telefono_itv VARCHAR(15),
    email_itv VARCHAR(100) NOT NULL,
    rol VARCHAR(20) DEFAULT 'alumno'
)";
$conn->query($tableAlumno);

$message = ""; // Variable para almacenar el mensaje

// Si se envió el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';

    // Consultar si el usuario existe y obtener el rol
    $stmt = $conn->prepare("SELECT id_alumno_itv, rol FROM alumno_itv WHERE email_itv = ? AND telefono_itv = ?");
    if ($stmt === false) {
        die("Error en la consulta: " . $conn->error);
    }
    
    $stmt->bind_param("ss", $email, $telefono);
    $stmt->execute();
    $result = $stmt->get_result();

    // Comprobar si hay resultados
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['id_alumno_itv'] = $row['id_alumno_itv']; // Almacena el ID del alumno en sesión
        $rol = $row['rol']; // Obtiene el rol del usuario

        // Redirigir según el rol del usuario
        if ($rol === 'admin') {
            header("Location: dashboard_admin_itv.php");
        } else {
            header("Location: dashboard_itv.php");
        }
        exit(); // Asegúrate de usar exit después de redirigir
    } else {
        $message = "<p style='color:red;'>Email o teléfono incorrectos.</p>";
    }

    // Cierra la consulta
    $stmt->close();
}

// Cierre de conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f2f2f2;
        }
        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Iniciar Sesión</h2>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Correo Electrónico" required>
        <input type="text" name="telefono" placeholder="Teléfono" required>
        <button type="submit">Iniciar Sesión</button>
    </form>
    <?php echo $message; ?>
</div>

</body>
</html>
