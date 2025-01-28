<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : null;
if (!$isLoggedIn) {
    header('Location: index.php'); // Redirect if not logged in
    exit();
}

// Include the database connection file
include('db_connect.php');

// Fetch all questions (or filter by subject if needed)
$query = "SELECT * FROM questions";
$result = $conn->query($query);
$questions = $result->fetch_all(MYSQLI_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mock Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-white text-black font-sans h-screen">
    <div class="flex justify-center items-center h-full">
        <div class="w-full max-w-2xl bg-white text-black p-8 rounded-lg shadow-lg">
            <div id="question-container" class="flex flex-col items-center">
                <!-- Question Card -->
                <div class="bg-white p-6 rounded-lg shadow-lg w-full mb-6" id="question-card">
                    <h2 id="question-text" class="text-xl font-bold mb-4">Loading Question...</h2>
                    <p id="topic" class="text-sm text-gray-600 mb-4">Subject: </p>
                    <div id="options" class="space-y-4">
                        <!-- Options will be loaded here dynamically -->
                    </div>
                    <p id="marks" class="text-sm mt-4">Marks: </p>
                </div>

                <div class="flex justify-between w-full">
                    <button id="skip-btn" class="bg-gray-500 text-white py-2 px-4 rounded-md">Skip</button>
                    <button id="next-btn" class="bg-green-500 text-white py-2 px-4 rounded-md hidden">Next</button>
                </div>

                <div class="mt-4 text-right">
                    <span id="timer" class="text-xl font-bold">1:00</span>
                </div>
                <div id="question-number" class="text-center text-xl mt-4">Question 1 of <?php echo count($questions); ?></div>
            </div>

            <!-- Submit Button -->
            <div id="submit-container" class="hidden text-center mt-6">
                <button id="submit-btn" class="bg-blue-600 text-white py-2 px-6 rounded-md">Submit</button>
            </div>
        </div>
    </div>

    <!-- Result Modal -->
    <div id="result-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-bold mb-4">Exam Completed!</h2>
            <p id="result-text" class="text-lg mb-4">Your result: </p>
            <div class="flex justify-between">
                <button id="home-btn" class="bg-blue-500 text-white py-2 px-4 rounded-md">Home</button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            let currentQuestionIndex = 0;
            let correctAnswers = 0;
            let totalMarks = 0;
            let questions = <?php echo json_encode($questions); ?>;
            let answers = [];
            let timer;
            let questionNumber = $('#question-number');
            
            // Load the question
            function loadQuestion() {
                const question = questions[currentQuestionIndex];
                $('#question-text').text(question.question_text);
                $('#topic').text('Subject: ' + question.subject);
                $('#marks').text('Marks: ' + question.marks);
                $('#options').empty();
                $('#options').append(`
                    <button class="option-btn w-full bg-black text-white py-2 rounded-md flex items-center" data-option="A">
                        <span class="option-text">${question.option_a}</span>
                        <span class="check-icon hidden text-green-500 ml-2">✔</span>
                    </button>
                    <button class="option-btn w-full bg-black text-white py-2 rounded-md flex items-center" data-option="B">
                        <span class="option-text">${question.option_b}</span>
                        <span class="check-icon hidden text-green-500 ml-2">✔</span>
                    </button>
                    <button class="option-btn w-full bg-black text-white py-2 rounded-md flex items-center" data-option="C">
                        <span class="option-text">${question.option_c}</span>
                        <span class="check-icon hidden text-green-500 ml-2">✔</span>
                    </button>
                    <button class="option-btn w-full bg-black text-white py-2 rounded-md flex items-center" data-option="D">
                        <span class="option-text">${question.option_d}</span>
                        <span class="check-icon hidden text-green-500 ml-2">✔</span>
                    </button>
                `);

                $('#next-btn').addClass('hidden');
                $('#timer').text('1:00');
                $('#question-number').text(`Question ${currentQuestionIndex + 1} of ${questions.length}`);
                startTimer();
            }

            // Start timer for each question
            function startTimer() {
                let timeLeft = 60;
                clearInterval(timer);  // Clear any existing timers
                timer = setInterval(function () {
                    timeLeft--;
                    $('#timer').text(`${Math.floor(timeLeft / 60)}:${timeLeft % 60}`);
                    if (timeLeft <= 0) {
                        clearInterval(timer);
                        nextQuestion();
                    }
                }, 1000);
            }

            // Go to next question
            function nextQuestion() {
                if (currentQuestionIndex < questions.length - 1) {
                    currentQuestionIndex++;
                    loadQuestion();
                } else {
                    $('#submit-container').removeClass('hidden');
                    $('#next-btn').addClass('hidden');
                }
            }

            // Option selection (only one option should be selected at a time)
            $(document).on('click', '.option-btn', function () {
                const selectedOption = $(this).data('option');

                // Deselect all options
                $('.option-btn').removeClass('selected');
                $('.option-btn .check-icon').addClass('hidden');

                // Select the clicked option
                $(this).addClass('selected');
                $(this).find('.check-icon').removeClass('hidden');

                // Store the answer
                answers[currentQuestionIndex] = selectedOption;
                $('#next-btn').removeClass('hidden');
            });

            // Skip button
            $('#skip-btn').click(function () {
                nextQuestion();
            });

            // Next button
            $('#next-btn').click(function () {
                nextQuestion();
            });

            // Submit button
            $('#submit-btn').click(function () {
                // Calculate the result
                let totalScore = 0;
                let wrongAnswers = 0;
                questions.forEach((question, index) => {
                    if (answers[index] === question.correct_answer) {
                        totalScore++;
                    } else if (answers[index]) {
                        wrongAnswers++;
                    }
                });

                totalScore -= wrongAnswers * 0.25;

                // Show result modal
                $('#result-text').text(`Your total score is: ${totalScore.toFixed(2)}`);
                $('#result-modal').removeClass('hidden');
            });

            // Home button in the modal
            $('#home-btn').click(function () {
                window.location.href = 'index.php'; // Redirect to home
            });

            // View results button
            $('#view-result-btn').click(function () {
                window.location.href = 'results.php'; // Redirect to view results
            });

            // Initial question load
            loadQuestion();

            $('#submit-btn').click(function () {
    // Calculate the result
    let totalScore = 0;
    let wrongAnswers = 0;
    questions.forEach((question, index) => {
        if (answers[index] === question.correct_answer) {
            totalScore++;
        } else if (answers[index]) {
            wrongAnswers++;
        }
    });

    totalScore -= wrongAnswers * 0.25;

    // Show result modal
    $('#result-text').text(`Your total score is: ${totalScore.toFixed(2)}`);
    $('#result-modal').removeClass('hidden');

    // Send the result to the database (AJAX request)
    $.ajax({
        url: 'save_results.php',  // PHP script to save results
        type: 'POST',
        data: {
            user_name: '<?php echo $userName; ?>',  // The logged-in user's name
            marks: totalScore.toFixed(2)  // The total score calculated
        },
        success: function(response) {
            console.log('Result saved successfully');
        },
        error: function(xhr, status, error) {
            console.error('Error saving result:', error);
        }
    });
});

        });
    </script>
</body>
</html>
