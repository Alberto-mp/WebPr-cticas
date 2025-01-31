<?php
// Configuración de la base de datos
$host = "localhost";
$user = "pruebajr";
$password = "pruebajr";
$dbname = "pruebas_practicas";

try {
    $conn = new mysqli($host, $user, $password, $dbname);
    $conn->set_charset("utf8");
} catch (mysqli_sql_exception $e) {
    die("<p style='color:red;'>Error de conexión: " . $e->getMessage() . "</p>");
}

// Obtener el parámetro de búsqueda
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($query !== '') {
    echo "<p>Buscando: <strong>$query</strong></p>"; // Mensaje temporal para depuración
}

// Preparar consulta segura
$sql = "SELECT ID, NAME, ADDRESS, TELF, TYPE FROM TEST_CLIENTS WHERE NAME LIKE ? OR ADDRESS LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$query%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Generar la tabla de resultados
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Tipo</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        $highlight = ($row['TYPE'] == 'P') ? "style='font-weight:bold; color:blue;'" : "";
        $type_display = ($row['TYPE'] == 'P') ? "⭐ Premium" : "Normal";
        echo "<tr $highlight>
                <td><a href='detalle.php?id={$row['ID']}'>{$row['NAME']}</a></td>
                <td>{$row['ADDRESS']}</td>
                <td>{$row['TELF']}</td>
                <td>$type_display</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red;'>No se encontraron clientes.</p>";
}

$conn->close();
?>
