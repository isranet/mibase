<?php
session_start(); // INICIAR SESIÓN

// CONEXIÓN A LA BASE DE DATOS
$servername = "localhost"; 
$username = "root"; // USUARIO PREDETERMINADO DE XAMPP
$password = ""; // CONTRASEÑA VACÍA POR DEFECTO EN XAMPP
$dbname = "mibase_itv";

// CREAR CONEXIÓN
$conn = new mysqli($servername, $username, $password, $dbname);

// VERIFICAR CONEXIÓN
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// OBTENER EL ID DEL ALUMNO LOGUEADO
$idAlumno = isset($_SESSION['id_alumno_itv']) ? $_SESSION['id_alumno_itv'] : null;

if ($idAlumno) {
    // CONSULTAR LA INFORMACIÓN DEL ALUMNO
    $stmt = $conn->prepare("SELECT nombre_itv FROM alumno_itv WHERE id_alumno_itv = ?");
    $stmt->bind_param("i", $idAlumno);
    $stmt->execute();
    $result = $stmt->get_result();

    // OBTENER EL NOMBRE DEL ALUMNO
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $usuarioNombre = $row['nombre_itv'];
    } else {
        $usuarioNombre = "Usuario"; // NOMBRE POR DEFECTO EN CASO DE NO ENCONTRAR
    }
} else {
    $usuarioNombre = "Invitado"; // NOMBRE POR DEFECTO
}

// CIERRE DE LA CONEXIÓN
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            width: 300px; /* Ancho fijo para un aspecto minimalista */
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            margin-bottom: 20px;
        }
        a {
            display: inline-block;
            margin: 10px 0;
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

<div class="dashboard-container">
    <h1>Bienvenido, <?php echo htmlspecialchars($usuarioNombre); ?>!</h1>
    <p>Este es tu dashboard. Desde aquí puedes acceder a tus materias y calificaciones.</p>

    <!-- Links para navegar a otras funcionalidades -->
    <a href="perfil_itv.php">Mi Perfil</a>
    <a href="materias_itv.php">Mis Materias</a>
    
    <a href="logout_itv.php">Cerrar Sesión</a> <!-- Asegúrate de crear esta página para cerrar sesión -->
</div>

</body>
</html>
