<?php
   // Include the login check
   require_once("check-login.php");
   
   // Connect to the database (update with your actual database credentials)
   $servername = "innodb.endora.cz";
   $username = "0ndra";
   $password = "Heslo1234";
   $dbname = "0ndradb";
   
   $conn = new mysqli($servername, $username, $password, $dbname);
   
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   
   // Retrieve questions from the database
   $questions = [];
   $result = $conn->query("SELECT * FROM quiz_soud ORDER BY RAND() LIMIT 20");
   
   if ($result->num_rows > 0) {
       while ($row = $result->fetch_assoc()) {
           $questions[] = $row;
       }
   } else {
       echo "No questions found.";
   }
   
   // Function to update the "TESTED" status for the user
   function markUserAsTested($user_id) {
       // Connect to the database (update with your actual database credentials)
       $servername = "innodb.endora.cz";
       $username = "0ndra";
       $password = "Heslo1234";
       $dbname = "0ndradb";
   
       $conn = new mysqli($servername, $username, $password, $dbname);
   
       if ($conn->connect_error) {
           die("Connection failed: " . $conn->connect_error);
       }
   
       // Update the "TESTED" column to mark the user as tested (set to 1)
       $update_query = "UPDATE usersq SET TESTED = 1 WHERE id = $user_id";
       if ($conn->query($update_query) === TRUE) {
           echo "User marked as tested.";
       } else {
           echo "Error: " . $update_query . "<br>" . $conn->error;
       }
   
       $conn->close();
   }
      // Handle the submission of the leaderboard data
   if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['player_score'])) {
       // Sanitize the input data
       $player_name = mysqli_real_escape_string($conn, $_SESSION['player_name']);
       $user_id = $_SESSION['user_id'];
       $player_score = intval($_POST['player_score']);
   
       // Insert the data into the leaderboard table
       $insert_query = "INSERT INTO leaderboard_table (name, score) VALUES ('$player_name', $player_score)";
       if ($conn->query($insert_query) === TRUE) {
           echo "Score saved successfully.";
         markUserAsTested($user_id);
       } else {
           echo "Error: " . $insert_query . "<br>" . $conn->error;
       }
   }
   $conn->close(); 
   
   ?>
<!DOCTYPE html>
<html lang="cs">
   <link type="text/css" rel="stylesheet" id="dark-mode-general-link">
   <link type="text/css" rel="stylesheet" id="dark-mode-custom-link">
   <link rel="stylesheet" href="./css/styles.css">
   <style lang="cz" type="text/css" id="dark-mode-custom-style">
   </style>
   <head>
      <meta charset="UTF-8">
      <title>„Test“</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <script src="leaderboard.js"></script>
   </head>
   <body>
      <div class="flex-container">
         <div class="flex-head">
            <h1>Test</h1>
         </div>
         <div class="flex-nav"> <a href="logout.php">Domů   </a></div>
         <div class="flex-section">
            <div>
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
                  // Check if the user has already taken the test
                  $user_id = $_SESSION['user_id'];
                  $result = $conn->query("SELECT TESTED FROM usersq WHERE id = $user_id");
                                                                                                                                                   
                  if ($result->num_rows > 0) {
                      $row = $result->fetch_assoc();
                      $tested_status = $row["TESTED"];
                      if (($tested_status == 1)|| (( $_SESSION['player_name']) == "l_vasicek_trb")) {
                          // User has already taken the test, prevent them from taking it again
                          $player_name =  $_SESSION['player_name'];
                          $score = $conn->query("SELECT score FROM leaderboard_table WHERE name = '$player_name'");
                          echo " <h2> Test jste již absolvovali. </h2>";
                          echo '<script type="text/javascript">','window.onload = showLeaderboard();','</script>';
                          //echo "Vaše skore je ".$score." bodů";
                       ?>
               <div id="leaderboard-container" style="display: <?php echo ( $_SESSION['player_name'])  == "a_vasickova_trb" || "l_vasicek_trb" ? 'block' : 'none'; ?>">
                  <h2>Výsledky</h2>
               </div>
               <br><a href='logout.php'>Logout</a>
            </div>
         </div>
         <div class="flex-foot">
            <p>©0ndra_m_ 2020-<?= date("Y");?>
         </div>
         <?php
            } 
            else   
            {
            ?>
         <div id="quiz-container">
            <div id="start-container" >
               <button id="start-button"onclick="startQuiz()">Start</button>
            </div>
            <div class="question" id="question-container" style="display: none;">
               <div class="grid-container">
                  <div id="question-number">
                     Otázka <span id="current-question-number">1</span>/
                     <span id="total-questions">
                        20 
                  </div>
                  <div id="timer-container"></span> Čas <span id="timer">05:00</span></div>
               </div>
               <h3 id="question-text"></h3>
               <label><input type="radio" name="answer" value="a"> <span id="option-a"></span></label>
               <br>
               <label><input type="radio" name="answer" value="b"> <span id="option-b"></span></label>
               <br>
               <label><input type="radio" name="answer" value="c"> <span id="option-c"></span></label>
               <br>
               <label><input type="radio" name="answer" value="d"> <span id="option-d"></span></label>
               <br>
               <br>
               <button onclick="submitAnswer(currentQuestion)">Další</button>
            </div>
         </div>
         <div id="result-container" style="display: none;text-align: center;">
            <h2>Výsledky testu</h2>
             </div>
               <div id="result-container2" style="display: none;text-align: center;">
            <p>Počet bodů: <span id="score">0</span></p>
         </div>
      </div>
      </div>
      <div class="flex-foot">
         <p>©0ndra_m_ 2020-<?= date("Y");?>
      </div>
      <script>      
         var currentQuestion = 0;
         var score = 0;
         var answers = [];
         var questions = <?php echo json_encode($questions); ?>;
         var quizDuration = 5 * 60; // 7 minutes in seconds
         var timerInterval;
         
         
         function updateQuestionNumber() {
             const currentQuestionNumber = currentQuestion + 1; // Convert to 1-based index
             document.getElementById('current-question-number').textContent = currentQuestionNumber;
         }
         
         // Function to start the quiz and show the first question
         function startQuiz() {
             document.getElementById('start-container').style.display = 'none';
             document.getElementById('question-container').style.display = 'block';
             document.getElementById('result-container2').style.display = 'block';

             showQuestion(currentQuestion);
                 startTimer();
         }
         
         function initializeQuiz() {
             showNextQuestion();
         }
         
         // Function to start the timer
         function startTimer() {
         const timerElement = document.getElementById('timer');
         const startTime = Date.now();
         const endTime = startTime + (quizDuration * 1000);
         
         // Update the timer every second
         timerInterval = setInterval(function () {
         const currentTime = Date.now();
         const remainingTime = Math.max(0, Math.floor((endTime - currentTime) / 1000));
         
         const minutes = Math.floor(remainingTime / 60);
         const seconds = remainingTime % 60;
         
         const formattedTime = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
         timerElement.textContent = formattedTime;
         
         if (remainingTime === 0) {
             clearInterval(timerInterval);
             // Handle quiz timeout here
               saveScore();
         document.getElementById('question-container').style.display = 'none';
         // Display the final score
         document.getElementById('result-container').style.display = 'block';
         
         showLeaderboard();
         }
         }, 1000); // Update every second
         }
         
         function showQuestion(questionIndex) {
             const question = questions[questionIndex];
             document.getElementById('question-text').textContent = question.question_text;
             document.getElementById('option-a').textContent = question.option_a;
             document.getElementById('option-b').textContent = question.option_b;
             document.getElementById('option-c').textContent = question.option_c;
             document.getElementById('option-d').textContent = question.option_d;
         }
         
         // Function to show the next question and clear radio button selection
         function showNextQuestion() {
         const selectedAnswer = document.querySelector('input[name="answer"]:checked');
         if (selectedAnswer) {
         selectedAnswer.checked = false;
         updateQuestionNumber();
         }
         
         if (currentQuestion < questions.length) {
         showQuestion(currentQuestion);
         updateQuestionNumber();
         } else {
           // All questions answered, hide the question container
           saveScore();
           document.getElementById('question-container').style.display = 'none';
           // Display the final score
           // Display the leaderboard  
           showLeaderboard();
         }
         }
         
         function submitAnswer(questionIndex) {
             const selectedAnswer = document.querySelector('input[name="answer"]:checked');
             if (!selectedAnswer) {
                 alert('Zvolte prosím odpověď.');
                 return;
             }
         
             const answerValue = selectedAnswer.value;
             const correctAnswer = questions[questionIndex].correct_answer;
             if (answerValue === correctAnswer) {
                 score++;
             }
         
             answers[questionIndex] = answerValue;
             document.getElementById('score').textContent = score;
         
             // Move to the next question
             currentQuestion++;
             showNextQuestion();
         }
         
         
         
         // Function to automatically save the player's score
         function saveScore() {
             const player_name = '<?php echo isset($_SESSION['player_name']) ? $_SESSION['player_name'] : ""; ?>';
             const player_score = score;
         
             // Use AJAX to save the score to the leaderboard table
             const xhr = new XMLHttpRequest();
             xhr.onreadystatechange = function () {
                 if (xhr.readyState === 4 && xhr.status === 200) {
                     console.log(xhr.responseText);
                     // Optionally, you can display a message to the user here
                 }
             };
             xhr.open('POST', 'quiz.php', true);
             xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
             xhr.send('player_name=' + encodeURIComponent(player_name) + '&player_score=' + encodeURIComponent(player_score));
         }
         
         // Call initializeQuiz when the page loads
         window.onload = initializeQuiz;
      </script>
      <?php 
         }
         } 
         
         else {
            echo "User not found.";
            exit;
         }
         $conn->close(); 
         ?>
   </body>
</html>