<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "es19";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
  die("Connessione fallita: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titolo = $conn->real_escape_string($_POST["titolo"]);
    $regista = $conn->real_escape_string($_POST["regista"]);
    $anno = $conn->real_escape_string($_POST["anno"]);
    $tipo = $conn->real_escape_string($_POST["tipo"]);
    $genere = $conn->real_escape_string($_POST["genere"]);

    $sql = "INSERT INTO video (titolo, regista, anno, tipo, IDgenere) VALUES ('$titolo', '$regista', '$anno', '$tipo', '$genere')
            ON DUPLICATE KEY UPDATE titolo = titolo, IDgenere = '$genere', anno = '$anno', tipo = '$tipo', regista = '$regista', numeroNoleggi = numeroNoleggi + 1";

    if ($conn->query($sql) === TRUE) {
        $message = "Video inserito/aggiornato correttamente!";
    } else {
        $message = "Errore inserimento/aggiornamento video: " . $conn->error;
    }
}

$queryGeneri = "SELECT ID, Descrizione FROM Genere";
$resultGeneri = $conn->query($queryGeneri);

$generiOptions = "";

if ($resultGeneri->num_rows > 0) {
    while ($row = $resultGeneri->fetch_assoc()) {
        $generiOptions .= "<option value='" . $row['ID'] . "'>" . $row['Descrizione'] . "</option>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ES 19</title>
  <style>
    body {
      background-color: #222;
      color: #fff;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    h1 {
      color: #3498db;
    }

    form {
      max-width: 400px;
      margin: 20px auto;
      background-color: #333;
      padding: 20px;
      border-radius: 8px;
    }

    input[type="text"],
    select {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      display: inline-block;
      border: 1px solid #3498db;
      box-sizing: border-box;
      background-color: #444;
      color: #fff;
    }

    select {
      color: #444;
    }

    button {
      background-color: #3498db;
      color: #fff;
      padding: 10px 15px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #2980b9;
    }

    a {
      display: block;
      text-align: center;
      margin-top: 20px;
      color: #3498db;
      text-decoration: none;
    }

    a:hover {
      color: #2980b9;
    }
  </style>
</head>
<body>
  <h1 style="text-align: center">Inserimento nuovo video</h1>

  <form action="index.php" method="post">
    Titolo: <input type="text" name="titolo"><br>
    Regista: <input type="text" name="regista"><br>
    Anno: <input type="text" name="anno"><br>
    Tipo: <select name="tipo">
      <option value="VHS">VHS</option>
      <option value="DVD">DVD</option>
    </select><br>
    Genere: <select name="genere">
      <?php echo $generiOptions; ?>
    </select><br><br>
    <button type="submit">Invia i dati</button><br>
  </form>

  <?php
    if (!empty($message)) {
      echo "<p>$message</p>";
    }
  ?>
  <a href="links.php"><button>Vai alla pagina dei link</button></a>
</body>
</html>