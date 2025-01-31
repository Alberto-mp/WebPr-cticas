<?php
// Configuración de la base de datos
$host = "localhost";
$user = "pruebajr";
$password = "pruebajr";
$dbname = "pruebas_practicas";

// Habilitar manejo de excepciones en MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Conectar a la base de datos
    $conn = new mysqli($host, $user, $password, $dbname);
    $conn->set_charset("utf8");
} catch (mysqli_sql_exception $e) {
    die("<p style='color:red;'>Error de conexión: " . $e->getMessage() . "</p>");
}

// Obtener el ID del cliente desde la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Consultar los datos del cliente usando una consulta preparada
        $stmt = $conn->prepare("SELECT * FROM TEST_CLIENTS WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

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
    } catch (mysqli_sql_exception $e) {
        echo "<p style='color:red;'>Error al recuperar datos: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color:red;'>ID de cliente no válido.</p>";
}

// Cerrar conexión
$conn->close();
?>
<br>
<a href="index.php">Volver al listado</a>
