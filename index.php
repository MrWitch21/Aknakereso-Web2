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
    <title>Aknakereső</title>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inclusive+Sans">
    <script src="js/index.js" defer></script>
</head>
<body>
    <?php if(isset($user)): ?>
        <header class="in">
            <p>Hello <?= htmlspecialchars( $user["name"])?>!</p>
            <a href="logout.php">Kijelentkezés</a>
        </header>
        <?php else: ?>
            <header class="out">
                <nav>
                    <ul>
                        <li><a href="login.php">Bejelentkezés</a></li>
                        <li>/</li>
                        <li><a href="register.html">Regisztráció</a></li>
                    </ul>
                </nav>
            </header>
        <?php endif; ?>
    <main>
        <h1>Aknakereső</h1>
        <button id="game" class="start-game">Játék</button>
        <button id="ranking" class="ranks">Ranglista</button>
    </main>
</body>
</html>