<?php
    $host = "";
    $dbname = "";
    $username = "";
    $password = "";

    $mysqli = new mysqli($host, $username, $password, $dbname);
    if ($mysqli->connect_error) {
        echo("Kapcsolódási hiba: " . $mysqli->connect_error);
    }
session_start();
if(isset($_SESSION["user_id"])){


    $sql = "SELECT * FROM user WHERE id = {$_SESSION["user_id"]} ";
    $result = $mysqli -> query($sql);

    $user = $result->fetch_assoc(); 
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/leaderboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inclusive+Sans">
    <title>Ranglista</title>
</head>
<body>
    <header>
    <div class="backToMain">
        <a href="index.php">← Vissza a főképernyőre</a>
    </div>
    </header>
    <h1>Ranglista</h1>
    <?php
        $sql = "SELECT user.name, leaderboard.size, leaderboard.difficulty, leaderboard.time FROM leaderboard INNER JOIN user ON leaderboard.user_id = user.id ORDER BY leaderboard.time ASC LIMIT 10";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Név</th><th>Pálya mérete</th><th>Nehézség</th><th>Befejezési idő</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["name"] . "</td><td>" . $row["size"] . "</td><td>" . $row["difficulty"] . "</td><td>" . $row["time"] . " mp</td></tr>";
            }
            echo "</table>";
        } else {
            echo "Nincsenek eredmények.";
        }
        $mysqli->close();
    ?>
</body>
</html>