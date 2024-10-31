<?php
session_start(); // Iniciar sesión

// Conexión a la base de datos
$servername = "localhost"; 
$username = "root"; // Usuario predeterminado de XAMPP
$password = ""; // Contraseña vacía por defecto en XAMPP
$dbname = "mibase_itv";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del alumno logueado
$idAlumno = isset($_SESSION['id_alumno_itv']) ? $_SESSION['id_alumno_itv'] : null;

if (!$idAlumno) {
    die("Acceso denegado. Debes iniciar sesión.");
}

// Consultar la información del alumno
$stmtAlumno = $conn->prepare("SELECT nombre_itv FROM alumno_itv WHERE id_alumno_itv = ?");
$stmtAlumno->bind_param("i", $idAlumno);
$stmtAlumno->execute();
$resultAlumno = $stmtAlumno->get_result();
$alumnoNombre = '';
if ($resultAlumno->num_rows > 0) {
    $rowAlumno = $resultAlumno->fetch_assoc();
    $alumnoNombre = htmlspecialchars($rowAlumno['nombre_itv']);
} else {
    die("Error al obtener los datos del alumno.");
}

// Consultar las materias y calificaciones del alumno
$sql = "SELECT m.nombre_materia_itv, c.calificacion_itv
        FROM materias_itv m
        JOIN calificaciones_itv c ON m.id_materia_itv = c.id_materia_itv
        WHERE c.id_alumno_itv = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idAlumno);
$stmt->execute();
$result = $stmt->get_result();

// Cierre de la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Materias</title>
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
        .materias-container {
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
        h2 {
            font-size: 28px; /* Tamaño grande para el nombre del alumno */
            margin: 0 0 10px;
            color: #333; /* Color del texto */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #4CAF50;
            border: 1px solid #4CAF50;
            padding: 10px 15px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }
        a:hover {
            background-color: #4CAF50;
            color: white;
        }
        .button-container {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .logout {
            color: #ff4c4c;
            border-color: #ff4c4c;
        }
        .logout:hover {
            background-color: #ff4c4c;
        }
    </style>
</head>
<body>

<div class="materias-container">
    <h2><?php echo $alumnoNombre; ?></h2> <!-- Nombre del alumno -->
    <h1>Mis Materias</h1>
    <table>
        <tr>
            <th>Materia</th>
            <th>Calificación</th>
        </tr>
        <?php
        // Mostrar las materias y calificaciones
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row['nombre_materia_itv']) . "</td><td>" . htmlspecialchars($row['calificacion_itv']) . "</td></tr>";
            }
        }
        ?>
    </table>
    <div class="button-container">
        <a href="dashboard_itv.php">Regresar</a> <!-- Botón para regresar -->
        <a href="logout.php" class="logout">Salir</a> <!-- Botón de logout -->
    </div>
</div>

</body>
</html>
