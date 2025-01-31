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

// Procesar formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $description = $_POST['description'];
    $telf = $_POST['telf'];
    $type = $_POST['type'];

    // Validar que los campos no estén vacíos
    if (!empty($name) && !empty($address) && !empty($telf) && !empty($type)) {
        $sql_insert = "INSERT INTO TEST_CLIENTS (NAME, ADDRESS, DESCRIPTION, TELF, TYPE) 
                       VALUES ('$name', '$address', '$description', '$telf', '$type')";
        if ($conn->query($sql_insert) === TRUE) {
            echo "<p style='color:green;'>Cliente agregado correctamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al agregar cliente: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Todos los campos son obligatorios.</p>";
    }
}

// Formulario para insertar nuevos clientes
echo '<h2>Agregar Nuevo Cliente</h2>
      <form method="POST">
          <label>Nombre:</label><br>
          <input type="text" name="name" required><br>
          <label>Dirección:</label><br>
          <input type="text" name="address" required><br>
          <label>Descripción:</label><br>
          <textarea name="description"></textarea><br>
          <label>Teléfono:</label><br>
          <input type="text" name="telf" required><br>
          <label>Tipo (N o P):</label><br>
          <input type="text" name="type" required maxlength="1"><br><br>
          <input type="submit" value="Agregar Cliente">
      </form>';

// Consulta para obtener los registros
$sql = "SELECT NAME, ADDRESS, TELF, TYPE FROM TEST_CLIENTS";
$result = $conn->query($sql);

// Mostrar resultados
if ($result->num_rows > 0) {
    echo "<h2>Listado de Clientes</h2>";
    echo "<table border='1'>
            <tr>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Tipo</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        $highlight = ($row['TYPE'] == 'P') ? "style='font-weight:bold; color:blue;'" : "";
        echo "<tr $highlight>
                <td>{$row['NAME']}</td>
                <td>{$row['ADDRESS']}</td>
                <td>{$row['TELF']}</td>
                <td>" . ($row['TYPE'] == 'P' ? "⭐ Premium" : "Normal") . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No hay registros en la base de datos.";
}

// Cerrar conexión
$conn->close();
?>
