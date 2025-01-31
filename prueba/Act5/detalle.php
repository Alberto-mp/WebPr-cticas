<?php
// Configuración de la base de datos
$host = "localhost";
$user = "pruebajr";
$password = "pruebajr";
$dbname = "pruebas_practicas";

// Conectar a la base de datos
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el ID del cliente desde la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Consultar los datos del cliente
    $sql = "SELECT * FROM TEST_CLIENTS WHERE ID = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<h2>Detalles del Cliente</h2>";
        echo "<p><strong>Nombre:</strong> {$row['NAME']}</p>";
        echo "<p><strong>Dirección:</strong> {$row['ADDRESS']}</p>";
        echo "<p><strong>Descripción:</strong> {$row['DESCRIPTION']}</p>";
        echo "<p><strong>Teléfono:</strong> {$row['TELF']}</p>";
        echo "<p><strong>Tipo:</strong> " . ($row['TYPE'] == 'P' ? "⭐ Premium" : "Normal") . "</p>";
    } else {
        echo "<p style='color:red;'>Cliente no encontrado.</p>";
    }
} else {
    echo "<p style='color:red;'>ID de cliente no válido.</p>";
}

// Cerrar conexión
$conn->close();
?>
<br>
<a href="index.php">Volver al listado</a>
