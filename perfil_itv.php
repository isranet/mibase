<?php
session_start();

// Conexión a la base de datos
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "mibase_itv";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del alumno logueado desde la sesión
$idAlumno = isset($_SESSION['id_alumno_itv']) ? $_SESSION['id_alumno_itv'] : null;

if (!$idAlumno) {
    die("Acceso denegado. Debes iniciar sesión.");
}

// Consultar la información del alumno
$sqlAlumno = "SELECT nombre_itv, fecha_nacimiento_itv, telefono_itv, email_itv 
              FROM alumno_itv 
              WHERE id_alumno_itv = ?";
$stmtAlumno = $conn->prepare($sqlAlumno);
$stmtAlumno->bind_param("i", $idAlumno);
$stmtAlumno->execute();
$resultAlumno = $stmtAlumno->get_result();

if ($resultAlumno->num_rows > 0) {
    $alumno = $resultAlumno->fetch_assoc();
} else {
    die("No se encontró la información del alumno.");
}

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Alumno</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f2f2f2;
        }
        .perfil-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 300px;
            text-align: center;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            margin: 10px 0;
        }
        .highlight {
            font-weight: bold;
            color: #4CAF50;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #4CAF50;
            border: 1px solid #4CAF50;
            padding: 10px 15px;
            border-radius: 4px;
        }
        a:hover {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>

<div class="perfil-container">
    <h1>Perfil del Alumno</h1>
    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($alumno['nombre_itv']); ?></p>
    <p><strong>Fecha de Nacimiento:</strong> <?php echo htmlspecialchars($alumno['fecha_nacimiento_itv']); ?></p>
    <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($alumno['telefono_itv']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($alumno['email_itv']); ?></p>
    <a href="dashboard_itv.php">Regresar al Dashboard</a>
</div>

</body>
</html>
