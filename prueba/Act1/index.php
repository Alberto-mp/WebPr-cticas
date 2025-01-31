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

// Consulta para obtener los registros
$sql = "SELECT NAME, ADDRESS, TELF FROM TEST_CLIENTS";
$result = $conn->query($sql);

// Mostrar resultados
if ($result->num_rows > 0) {
    echo "<h2>Listado de Clientes</h2>";
    echo "<table border='1'>
            <tr>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Teléfono</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['NAME']}</td>
                <td>{$row['ADDRESS']}</td>
                <td>{$row['TELF']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No hay registros en la base de datos.";
}

// Cerrar conexión
$conn->close();
?>
