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
    $sql = "SELECT * FROM user WHERE id = {$_SESSION["user_id"]}";
    $result = $mysqli -> query($sql);

    $user = $result->fetch_assoc();
    if(isset($_POST)){
       $data = file_get_contents("php://input");
       $gameData = json_decode($data,true);
    }
    $gameTime = $gameData["gametime"];
    $gameDif = $gameData["dif"];
    $gameSize = $gameData["size"];
    $sql = "INSERT INTO leaderboard (difficulty,size, time, user_id)  VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->stmt_init();
    if(! $stmt->prepare($sql)){
        echo("SQL error: ". $mysqli->error);
    }
    $stmt->bind_param("siii",$gameDif,$gameSize,$gameTime,$user["id"]);
    if ($stmt->execute()) {
        echo "Az eredmény sikeresen rögzítve a leaderboard-ben.";
    } else {
        echo "Hiba történt az eredmény rögzítése közben.";
    }
    $stmt->close();
    $mysqli->close();
}