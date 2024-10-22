<?php
// Parámetros de conexión
$servername = "localhost";
$username = "root";  // Cambia si es necesario
$password = "";  // Cambia si es necesario
$dbname = "form_responses";  // Cambia al nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $name = $_POST['name'];

    // Inicializar el puntaje
    $score = 0;

    // Calcular el puntaje
    for ($i = 1; $i <= 5; $i++) {
        if (isset($_POST["q$i"])) {
            $score += intval($_POST["q$i"]);
        }
    }

    // Insertar el nombre y puntaje en la base de datos
    $sql = "INSERT INTO datos (name, score) VALUES ('$name', $score)";

    if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Consultar los datos de la tabla para mostrarlos en el leaderboard
$sql = "SELECT name, score FROM datos ORDER BY score DESC";
$result = $conn->query($sql);
?>

<h2>Tabla de Posiciones</h2>
<style>
    #leaderboard {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 18px;
        font-family: Arial, sans-serif;
    }
    
    #leaderboard thead {
        background-color: #4CAF50; /* Color de fondo del encabezado */
        color: white; /* Color del texto del encabezado */
    }
    
    #leaderboard th, #leaderboard td {
        padding: 12px; /* Espaciado interno */
        text-align: left; /* Alineación del texto */
        border-bottom: 1px solid #ddd; /* Línea inferior de las celdas */
    }
    
    #leaderboard td {
        background-color: #ffffff; /* Color de fondo de las celdas */
    }

    #leaderboard td:first-child {
        font-weight: bold; /* Negrita para la posición */
    }
</style>

<table id="leaderboard">
    <thead>
        <tr>
            <th>Posición</th>
            <th>Nombre</th>
            <th>Puntaje</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            $position = 1;
            // Mostrar los datos en la tabla
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $position++ . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>"; // Escapar salida
                echo "<td>" . htmlspecialchars($row['score']) . "</td>"; // Escapar salida
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No hay datos disponibles</td></tr>";
        }
        ?>
    </tbody>
</table>

<!-- Botón de Volver -->
<div style="text-align: center; margin-top: 20px;">
    <a href="index.html" style="text-decoration: none;">
        <button style="
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            transition: background-color 0.3s;">
            Volver
        </button>
    </a>
</div>

<?php
$conn->close();
?>
