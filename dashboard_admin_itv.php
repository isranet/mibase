<?php
session_start();

// Configuración de la base de datos
$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "mibase_itv";

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener lista de alumnos
$alumnos = $conn->query("SELECT id_alumno_itv, nombre_itv FROM alumno_itv");

// Procesar actualización de calificaciones
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_alumno'])) {
    $idAlumno = $_POST['id_alumno'];
    foreach ($_POST['calificaciones'] as $idMateria => $calificacion) {
        $stmt = $conn->prepare("UPDATE calificaciones_itv SET calificacion_itv = ? WHERE id_alumno_itv = ? AND id_materia_itv = ?");
        $stmt->bind_param("dii", $calificacion, $idAlumno, $idMateria);
        $stmt->execute();
    }
    $mensaje = "Calificaciones actualizadas correctamente.";
}

// Cargar materias y calificaciones de un alumno seleccionado
$materias = [];
if (isset($_POST['id_alumno'])) {
    $idAlumno = $_POST['id_alumno'];
    $materiasResult = $conn->query("SELECT m.id_materia_itv, m.nombre_materia_itv, c.calificacion_itv
                                    FROM materias_itv m
                                    LEFT JOIN calificaciones_itv c ON m.id_materia_itv = c.id_materia_itv AND c.id_alumno_itv = $idAlumno");
    $materias = $materiasResult->fetch_all(MYSQLI_ASSOC);
}

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Modificar Calificaciones</title>
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
        .dashboard-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
            width: 300px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        select, input[type="number"], button {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            width: 100%;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .mensaje {
            color: green;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h1>Modificar Calificaciones</h1>
    
    <?php if (isset($mensaje)): ?>
        <p class="mensaje"><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="id_alumno">Selecciona un alumno:</label>
        <select name="id_alumno" id="id_alumno" onchange="this.form.submit()">
            <option value="">-- Seleccionar --</option>
            <?php while ($alumno = $alumnos->fetch_assoc()): ?>
                <option value="<?php echo $alumno['id_alumno_itv']; ?>" <?php if (isset($idAlumno) && $idAlumno == $alumno['id_alumno_itv']) echo 'selected'; ?>>
                    <?php echo $alumno['nombre_itv']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <?php if (!empty($materias)): ?>
        <form method="POST">
            <input type="hidden" name="id_alumno" value="<?php echo $idAlumno; ?>">
            <?php foreach ($materias as $materia): ?>
                <label><?php echo $materia['nombre_materia_itv']; ?></label>
                <input type="number" step="0.01" name="calificaciones[<?php echo $materia['id_materia_itv']; ?>]" value="<?php echo $materia['calificacion_itv']; ?>" min="0" max="100">
            <?php endforeach; ?>
            <button type="submit">Guardar Calificaciones</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
