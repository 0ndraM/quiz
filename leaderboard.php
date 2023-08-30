<?php
$servername = "innodb.endora.cz";
$username = "0ndra";
$password = "Heslo1234";
$dbname = "0ndradb";


// Connect to the database (same as in quiz.php)
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve leaderboard data from the database
$leaderboardData = [];
$leaderboardResult = $conn->query("SELECT name, score FROM leaderboard_table ORDER BY score DESC LIMIT 10");

if ($leaderboardResult->num_rows > 0) {
    while ($row = $leaderboardResult->fetch_assoc()) {
        $leaderboardData[] = $row;
    }
}

$conn->close();

// Send the leaderboard data as JSON
header('Content-Type: application/json');
echo json_encode($leaderboardData);
?>
