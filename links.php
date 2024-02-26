<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>links</title>

    <style>
        body {
            background-color: #222;
            color: #0077FF;
        }

        h1, h2, h3, h4, h5, h6 {
            color: #fff;
        }

        a {
            color: #0077FF;
            text-decoration: none;
        }

        a:hover {
            color: #0099FF;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            padding: 5px 0;
        }

        button {
            background-color: #0077FF;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
        }

        button:hover {
            background-color: #0099FF;
        }
    </style>
</head>
<body>
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

$queryGeneri = "SELECT * FROM Genere";
$resultGeneri = $conn->query($queryGeneri);

if ($resultGeneri->num_rows > 0) {
    while ($rowGenere = $resultGeneri->fetch_assoc()) {
        $genereID = $rowGenere['ID'];
        $genereDescrizione = $rowGenere['Descrizione'];

        $queryFilm = "SELECT v.titolo, COUNT(p.IDVideo) as numeroNoleggi
                      FROM Video v
                      JOIN Prestiti p ON v.ID = p.IDVideo
                      WHERE v.IDgenere = $genereID
                      GROUP BY v.titolo
                      ORDER BY numeroNoleggi DESC";

        $resultFilm = $conn->query($queryFilm);

        echo "<h2>Film del genere $genereDescrizione</h2>";

        if ($resultFilm->num_rows > 0) {
            echo "<ul>";
            while ($rowFilm = $resultFilm->fetch_assoc()) {
                $titoloFilm = $rowFilm['titolo'];
                $numeroNoleggi = $rowFilm['numeroNoleggi'];

                echo "<li>$titoloFilm - Numero noleggi: $numeroNoleggi</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Nessun film disponibile per questo genere.</p>";
        }
    }
} else {
    echo "<p>Nessun genere disponibile.</p>";
}

$conn->close();
?>
    <a href="index.php"><button>Torna alla home</button></a>
</body>
</html>