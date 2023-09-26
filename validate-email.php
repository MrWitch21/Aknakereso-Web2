<?php

$host = "";
$dbname = "";
$username = "";
$password = "";

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_error) {
    echo("Kapcsolódási hiba: " . $mysqli->connect_error);
}

$sql = sprintf("SELECT * FROM user WHERE email = '%s'", $mysqli->real_escape_string($_GET["email"]));
                
$result = $mysqli->query($sql);

$is_available = $result->num_rows === 0;

header("Content-Type: application/json");

echo json_encode(["available" => $is_available]);