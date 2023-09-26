<?php
$host = "";
$dbname = "";
$username = "";
$password = "";

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_error) {
    echo("Kapcsolódási hiba: " . $mysqli->connect_error);
}

//email
if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
    echo("Érvényes email cím szükséges"); //Valid email
}
//username
if (empty($_POST["username"])){
    echo("A felhasználó név mező kötelező"); //name is requed
}
//jelszó
if(strlen($_POST["password"])<8){
    echo("A jelszónak legalább 8 karekter hosszúnak kell lennie!");
}
if(! preg_match("/[a-z]/i", $_POST["password"])){
    echo("A jelszónak tartalmaznia kell egy kis betűt!");
}
if(! preg_match("/[A-Z]/i", $_POST["password"])){
    echo("A jelszónak tartalmaznia kell egy nagy betűt!");
}
if(! preg_match("/[0-9]/i", $_POST["password"])){
    echo("A jelszónak tartalmaznia kell egy számot!");
}
//jelszó2 
if($_POST["password"] !== $_POST["password-confirm"]){
    echo("A két jelszó nem egyezik!");
}

$sql = "INSERT INTO user (name,email, password_hash)  VALUES (?, ?, ?)";
$stmt = $mysqli->stmt_init();

if(! $stmt->prepare($sql)){
    echo("sql error: ". $mysqli->error);
    
}
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
$stmt->bind_param("sss",
            $_POST["username"],
            $_POST["email"],
            $password_hash );
if($stmt->execute()){
    echo("Sikeres regisztráció");
    header("Location: signupS.html");
    $mysqli->close();
    exit;
}else{
    if($mysqli->errno === 1062)
    {
        echo("Ezzel az emailel vagy felhasználónévvel már regisztráltak.");
        $mysqli->close();
        
    }else{
        echo($mysqli->error . " " . $mysqli->errno);
        $mysqli->close();
    }
}
?>