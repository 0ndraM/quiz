<!DOCTYPE html>
<html lang="cs">
<link type="text/css" rel="stylesheet" id="dark-mode-general-link">
<link type="text/css" rel="stylesheet" id="dark-mode-custom-link">
<link rel="stylesheet" href="./css/styles.css">
<style lang="cz" type="text/css" id="dark-mode-custom-style"></style>
<head>
<meta charset="UTF-8">
    <title>„Registrace“</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="format.js"></script>
</head>
<body>
    <div class="flex-container">

    <div class="flex-head"><h1>„Registrace“</h1>
    </div>
    <div class="flex-nav"> <a href="logout.php">Domů   </a></div>

    <div class="flex-section">
<?php
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
    $newUsername = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Check if the username already exists in the database
    $checkSql = "SELECT * FROM usersq WHERE username = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $newUsername);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        echo "živatelské jméno je již obsazeno. Zvolte prosím jiné uživatelské jméno.";
    } else {
        $insertSql = "INSERT INTO usersq (username, password) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("ss", $newUsername, $password);

        if ($insertStmt->execute()) {
                      echo "<div class='form'>
                  <h3>Jste úspěšně zaregistrováni.</h3><br/>
                  <p class='link'>Klikni zde pro <a href='login.php'>přihlášení.</a></p>
                  </div>";
        } else {
            echo "Error: " . $insertSql . "<br>" . $conn->error;
        }

        $insertStmt->close();
    }

    $checkStmt->close();
}

$conn->close();
?>

        <div>
    <form class="form" method="post" name="login">
        <h1 class="login-title">Registruj se</h1>
        <p>      
    <label class="w3-text-brown"><b>Jméno</b></label>
    <input type="text" id="username" name="username" oninput="checkFormat()" />
    <span id="usernameError" style="color: red;"></span>
    <br>        <p>      
<label class="w3-text-brown"><b>Heslo  </b></label>
    <input class="w3-input w3-border w3-aqua" name="password" type="password"required></p>
            <button class="w3-btn w3-blue">Registrovat se</button></p>
                    <p class="link">Máte již účet? <a href="login.php">Přihlaste se zde</a></p>
    </form>

        </div>
    </div>
    <div class="flex-foot">
            <p>©0ndra_m_ 2020-<?= date("Y");?>
    </div>
</body>
</html>
