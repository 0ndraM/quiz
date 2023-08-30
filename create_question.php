<?php
    $conn = new mysqli("innodb.endora.cz","0ndra","Heslo1234","0ndradb");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Question</title>
    <style>
        /* Add your CSS styles here */
    </style>
</head>
<body>
    <div>
        <?php

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT  id, question_text, option_a, option_b, option_c, option_d, correct_answer FROM quiz_soud";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
     // Create the table
    echo "<table>";
    echo "<tr>";
    echo "<th>Id</th>";
    echo "<th>Otázka</th>";
    echo "<th>Odpověď A</th>";
    echo "<th>Odpověď B</th>";
    echo "<th>Odpověď C</th>";
    echo "<th>Odpověď D</th>";
    echo "<th>Správná dpověď</th>";
    echo "</tr>";
    
    // Loop through the results and create the rows
    while ($row = $result->fetch_assoc()) {
        $timestamp = date('H:i', strtotime($row["timestamp"]));
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['question_text'] . "</td>";
        echo "<td>" . $row['option_a'] . "</td>";
        echo "<td>" . $row['option_b'] . "</td>";
        echo "<td>" . $row['option_c'] . "</td>";
        echo "<td>" . $row['option_d'] . "</td>";
        echo "<td>" . $row['correct_answer'] . "</td>";
        
        echo "</tr>";
    }
    
    // Close the table
    echo "</table>";

} else {
    echo "Zatím žádné otázky";
}
$conn->close();
?>
    </div>
    <div>
    <h1>Přidej otázku</h1>
    <form action="add_question.php" method="POST">
        <label for="question">Otázka:</label><br>
        <textarea id="question" name="question" rows="4" cols="50" required></textarea><br><br>

        <label for="option_a">Možnost A:</label><br>
        <input type="text" id="option_a" name="option_a" required><br><br>

        <label for="option_b">Možnost B:</label><br>
        <input type="text" id="option_b" name="option_b" required><br><br>

        <label for="option_c">Možnost C:</label><br>
        <input type="text" id="option_c" name="option_c" required><br><br>

        <label for="option_d">Možnost D:</label><br>
        <input type="text" id="option_d" name="option_d" required><br><br>

        <label for="correct_answer">Správná odpověď (a/b/c/d):</label><br>
        <input type="text" id="correct_answer" name="correct_answer" required><br><br>

        <input type="submit" value="Uložit ">
    </form>
    </div>
</body>
</html>
