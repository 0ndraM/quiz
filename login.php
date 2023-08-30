<?php
session_start();

// Connect to the database (replace with your database details)
$servername = "innodb.endora.cz";
$username = "0ndra";
$password = "Heslo1234";
$dbname = "0ndradb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT id,  username, password FROM usersq WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $dbUsername, $dbPassword);

    if ($stmt->fetch() && password_verify($password, $dbPassword)) {
        $_SESSION["user_id"] = $id;
        $_SESSION["player_name"] = $dbUsername;
        header("Location: quiz.php"); // Redirect to the dashboard or any other page
    } else {
        echo "Invalid username or password.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="cs">
<link type="text/css" rel="stylesheet" id="dark-mode-general-link">
<link type="text/css" rel="stylesheet" id="dark-mode-custom-link">
<link rel="stylesheet" href="./css/styles.css">
<style lang="cz" type="text/css" id="dark-mode-custom-style"></style>¨
    <script src="format.js"></script>

<head>
<meta charset="UTF-8">
    <title>„Login“</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<body>
    <div class="flex-container">

    <div class="flex-head"><h1>„Login“</h1>
    </div>
    <div class="flex-nav"> <a href="logout.php">Domů   </a></div>

    <div class="flex-section">
        <div>
        <form class="form" method="post" name="login">
        <h1 class="login-title">Login</h1>
        <p>      
    <label class="w3-text-brown"><b>Jméno</b></label>
    <input type="text" id="username" name="username" oninput="checkFormat()" />
    <span id="usernameError" style="color: red;"></span>
    <p>       
<label class="w3-text-brown"><b>Heslo</b></label>
    <input class="w3-input w3-border w3-aqua" name="password" type="password" required ></p>
            <button class="w3-btn w3-blue">Přihlásit</button></p>
        <p class="link">Nemáte účet? <a href="register.php">Registrujte se nyní!</a></p>
  </form>

        </div>
    </div>
    <div class="flex-foot">
            <p>©0ndra_m_ 2020-<?= date("Y");?>
    </div>

</body>
</html>
