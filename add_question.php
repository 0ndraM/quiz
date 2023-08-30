<?php
// Connect to the database (update with your actual database credentials)
$servername = "innodb.endora.cz";
$username = "0ndra";
$password = "Heslo1234";
$dbname = "0ndradb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the question details from the form
$question = $_POST['question'];
$option_a = $_POST['option_a'];
$option_b = $_POST['option_b'];
$option_c = $_POST['option_c'];
$option_d = $_POST['option_d'];
$correct_answer = $_POST['correct_answer'];

// Check if the question already exists
$check_query = "SELECT * FROM quiz_soud WHERE question_text = '$question'";
$result = $conn->query($check_query);

if ($result->num_rows > 0) {
    echo "Error: This question already exists in the database.";
} else {
    // Insert the question into the database
    $insert_query = "INSERT INTO quiz_soud (question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES ('$question', '$option_a', '$option_b', '$option_c', '$option_d', '$correct_answer')";

    if ($conn->query($insert_query) === TRUE) {
        echo "Question added successfully.";
    } else {
        echo "Error: " . $insert_query . "<br>" . $conn->error;
    }
}

$conn->close();
?>
