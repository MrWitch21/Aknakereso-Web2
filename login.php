<?php
$is_invalid = false;
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $host = "";
    $dbname = "";
    $username = "";
    $password = "";
    $mysqli = new mysqli($host, $username, $password, $dbname);
     
    if ($mysqli->connect_error) {
        echo("Kapcsolódási hiba: " . $mysqli->connect_error);
    }
    $sql = sprintf("SELECT * FROM user
            WHERE email = '%s'",
            $mysqli->real_escape_string($_POST["email"]));
    $result = $mysqli->query($sql);
    $user = $result->fetch_assoc();
    if($user){
       if(password_verify($_POST["password"], $user["password_hash"])){
            session_start();

            session_regenerate_id();
            $_SESSION["user_id"] = $user["id"];
            header("Location: index.php");
            exit;
        }
    }
    $is_invalid = true;
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inclusive+Sans">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <link rel ="stylesheet" type="text/css" href="css/header.css">
</head>
<body>
    <header>
        <nav>
            <div class="backToMain">
                <a href="index.php">← Vissza a főképernyőre</a>
            </div>
            <div class="log_reg">
                <ul>
                    <li><a href="#" class="active">Bejelentkezés</a></li>
                    <li>/</li>
                    <li><a href="register.html">Regisztráció</a></li>
                </ul>
            </div>
            </nav>
    </header>
    <main>
        <h1>Bejelentkezés</h1>
        <?php if($is_invalid):?>
            <em>Érvénytelen bejelentkezés</em>
        <?php endif; ?>

        <form method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST["email"] ?? "")?>"required><br><br>
            <label for="password">Jelszó:</label>
            <input type="password" id="password" name="password" required><br><br>
            <button type="submit" class="login">Bejelentkezés</button>
        </form>
    </main>
</body>
</html>