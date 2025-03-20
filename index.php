<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "carlos";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["firstname"]) && isset($_POST["lastname"]) && !isset($_POST["id"])) {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $sql = "INSERT INTO mytable (firstname, lastname) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $firstname, $lastname);
    $stmt->execute();
    $stmt->close();
}

// Update record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"]) && isset($_POST["firstname"]) && isset($_POST["lastname"])) {
    $id = $_POST["id"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $sql = "UPDATE mytable SET firstname=?, lastname=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $firstname, $lastname, $id);
    $stmt->execute();
    $stmt->close();
}

// Delete record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["firstname"]) && !isset($_POST["lastname"]) && !isset($_POST["id"])) {
    $firstname = $_POST["firstname"];
    $sql = "DELETE FROM mytable WHERE firstname=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $firstname);
    $stmt->execute();
    $stmt->close();
}

$sql = "SELECT id, firstname, lastname FROM mytable";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$rows = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
} else {
    $rows = null;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Application</title>
</head>
<body>
<header style="text-align: center; padding: 20px; background-color: #f1f1f1; border-bottom: 1px solid #ccc;">
    <h1>CRUD Application</h1>
</header>

<h2>Agregar Nuevo Registro</h2>
<form action="" method="post">
    Nombre: <input type="text" name="firstname" style="width: 50%;"><br>
    Apellido: <input type="text" name="lastname" style="width: 50%;"><br>
    <input type="submit" value="Agregar">
</form>

<h2>Modificar Registro</h2>
<form action="" method="post">
    ID: <input type="text" name="id" style="width: 50%;"><br>
    Nombre: <input type="text" name="firstname" style="width: 50%;"><br>
    Apellido: <input type="text" name="lastname" style="width: 50%;"><br>
    <input type="submit" value="Modificar">
</form>

<h2>Eliminar Registro</h2>
<form action="" method="post">
    Nombre: <input type="text" name="firstname" style="width: 50%;"><br>
    <input type="submit" value="Eliminar">
</form>

<h2>Registros</h2>
<?php if ($rows): ?>
<table border="1" style="width: 100%; border-collapse: collapse;">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellido</th>
    </tr>
    <?php foreach ($rows as $row): ?>
    <tr>
        <td><?php echo htmlspecialchars($row["id"]); ?></td>
        <td><?php echo htmlspecialchars($row["firstname"]); ?></td>
        <td><?php echo htmlspecialchars($row["lastname"]); ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>No hay resultados</p>
<?php endif; ?>
<footer style="text-align: center; margin-top: 20px; padding: 10px; background-color: #f1f1f1; border-top: 1px solid #ccc;">
    <p>Creado por: Carlos Arnolodo y Fabiola Alejandra</p>
</footer>
</body>
</html>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }
    h2 {
        color: #333;
    }
    form {
        margin-bottom: 20px;
    }
    input[type="text"] {
        padding: 5px;
        margin: 5px 0;
        width: 100%;
        box-sizing: border-box;
    }
    input[type="submit"] {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background-color: #45a049;
    }
    table {
        margin-top: 20px;
    }
    th, td {
        padding: 10px;
        text-align: left;
    }
</style>
<script>
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!confirm('¿Estás seguro de que deseas enviar este formulario?')) {
                event.preventDefault();
            }
        });
    });
</script>