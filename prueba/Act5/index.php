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
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $description = trim($_POST['description']);
    $telf = trim($_POST['telf']);
    $type = strtoupper(trim($_POST['type'])); // Convertir a mayúsculas y quitar espacios

    // Validar que los campos no estén vacíos y que TYPE sea 'N' o 'P'
    if (!empty($name) && !empty($address) && !empty($telf)) {
        if (!preg_match('/^\d+$/', $telf)) { // Verificar que TELF solo tenga números
            echo "<p style='color:red;'>Error: El teléfono solo puede contener números.</p>";
        } elseif ($type == 'N' || $type == 'P') {
            $sql_insert = "INSERT INTO TEST_CLIENTS (NAME, ADDRESS, DESCRIPTION, TELF, TYPE) 
                           VALUES ('$name', '$address', '$description', '$telf', '$type')";
            if ($conn->query($sql_insert) === TRUE) {
                echo "<p style='color:green;'>Cliente agregado correctamente.</p>";
            } else {
                echo "<p style='color:red;'>Error al agregar cliente: " . $conn->error . "</p>";
            }
        } else {
            echo "<p style='color:red;'>Error: El campo 'Tipo' solo acepta los valores 'N' o 'P'.</p>";
        }
    } else {
        echo "<p style='color:red;'>Todos los campos son obligatorios.</p>";
    }
}

// Formulario para insertar nuevos clientes
echo '<h2>Agregar Nuevo Cliente</h2>
      <form id="clientForm" method="POST" onsubmit="return validarFormulario()">
          <label>Nombre:</label><br>
          <input type="text" name="name" id="name" required><br>
          <label>Dirección:</label><br>
          <input type="text" name="address" id="address" required><br>
          <label>Descripción:</label><br>
          <textarea name="description" id="description"></textarea><br>
          <label>Teléfono:</label><br>
          <input type="number" name="telf" id="telf" required><br>
          <label>Tipo (N o P):</label><br>
          <input type="text" name="type" id="type" required maxlength="1"><br><br>
          <input type="submit" value="Agregar Cliente">
      </form>

      <script>
          function validarFormulario() {
              let name = document.getElementById("name").value.trim();
              let address = document.getElementById("address").value.trim();
              let telf = document.getElementById("telf").value.trim();
              let type = document.getElementById("type").value.trim().toUpperCase();

              if (name === "" || address === "" || telf === "" || type === "") {
                  alert("Todos los campos son obligatorios.");
                  return false;
              }

              if (!/^\d+$/.test(telf)) {
                  alert("El teléfono debe contener solo números.");
                  return false;
              }

              if (type !== "N" && type !== "P") {
                  alert("El campo "Tipo" solo acepta "N" o "P".");
                  return false;
              }

              return true;
          }
      </script>';

// Consulta para obtener los registros
$sql = "SELECT ID, NAME, ADDRESS, TELF, TYPE FROM TEST_CLIENTS";
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
        $type_display = ($row['TYPE'] == 'P') ? "⭐ Premium" : "Normal";

        // Agregar enlace al nombre para ver detalles
        echo "<tr $highlight>
                <td><a href='detalle.php?id={$row['ID']}'>{$row['NAME']}</a></td>
                <td>{$row['ADDRESS']}</td>
                <td>{$row['TELF']}</td>
                <td>$type_display</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No hay registros en la base de datos.";
}


// Cerrar conexión
$conn->close();
?>
