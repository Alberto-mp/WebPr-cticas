<?php
// Configuración de la base de datos
$host = "localhost";
$user = "pruebajr";
$password = "pruebajr";
$dbname = "pruebas_practicas";

// Habilitar manejo de errores en MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Conectar a la base de datos con manejo de errores
    $conn = new mysqli($host, $user, $password, $dbname);
    $conn->set_charset("utf8");
} catch (mysqli_sql_exception $e) {
    die("<p style='color:red;'>Error de conexión: " . $e->getMessage() . "</p>");
}

// Procesar formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $description = trim($_POST['description']);
    $telf = trim($_POST['telf']);
    $type = strtoupper(trim($_POST['type']));

    // Validar datos
    if (!empty($name) && !empty($address) && !empty($telf)) {
        if (!preg_match('/^\d+$/', $telf)) {
            echo "<p style='color:red;'>Error: El teléfono solo puede contener números.</p>";
        } elseif ($type == 'N' || $type == 'P') {
            try {
                // Usar consulta preparada para evitar SQL Injection
                $stmt = $conn->prepare("INSERT INTO TEST_CLIENTS (NAME, ADDRESS, DESCRIPTION, TELF, TYPE) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $name, $address, $description, $telf, $type);
                $stmt->execute();
                echo "<p style='color:green;'>Cliente agregado correctamente.</p>";
            } catch (mysqli_sql_exception $e) {
                echo "<p style='color:red;'>Error al agregar cliente: " . $e->getMessage() . "</p>";
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
          <input type="text" name="telf" id="telf" required><br>
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

// Consulta para obtener los registros con manejo de errores
try {
    $sql = "SELECT ID, NAME, ADDRESS, TELF, TYPE FROM TEST_CLIENTS";
    $result = $conn->query($sql);
} catch (mysqli_sql_exception $e) {
    die("<p style='color:red;'>Error al recuperar datos: " . $e->getMessage() . "</p>");
}

// Formulario de búsqueda
echo '<h2>Buscar Clientes</h2>
      <input type="text" id="search" placeholder="Escribe para buscar..." onkeyup="buscarClientes()">
      <div id="clientes"></div>

      <script>
          function buscarClientes() {
              let query = document.getElementById("search").value;
              console.log("Buscando: " + query); // Ver si la búsqueda se está enviando
              let xhr = new XMLHttpRequest();
              xhr.onreadystatechange = function() {
                  if (xhr.readyState == 4 && xhr.status == 200) {
                      document.getElementById("clientes").innerHTML = xhr.responseText;
                  }
              };
              xhr.open("GET", "buscar.php?q=" + encodeURIComponent(query), true);
              xhr.send();
          }

          // Cargar la lista completa al inicio
          window.onload = buscarClientes;
      </script>';


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
        echo "<tr $highlight>
                <td><a href='detalle.php?id={$row['ID']}'>{$row['NAME']}</a></td>
                <td>{$row['ADDRESS']}</td>
                <td>{$row['TELF']}</td>
                <td>$type_display</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red;'>No hay registros en la base de datos.</p>";
}

// Cerrar conexión
$conn->close();
?>
