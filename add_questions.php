<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

require 'db_connect.php'; // Include database connection

$examId = $_GET['exam_id'] ?? null;
$totalQuestions = $_GET['total_questions'] ?? null;

if (!$examId || !$totalQuestions) {
    die("Invalid request!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insert questions into the database
    for ($i = 1; $i <= $totalQuestions; $i++) {
        $subject = $_POST["subject_$i"];
        $questionText = $_POST["question_$i"];
        $optionA = $_POST["option_a_$i"];
        $optionB = $_POST["option_b_$i"];
        $optionC = $_POST["option_c_$i"];
        $optionD = $_POST["option_d_$i"];
        $correctAnswer = $_POST["correct_answer_$i"];

        $stmt = $conn->prepare(
            "INSERT INTO questions (subject, question_text, option_a, option_b, option_c, option_d, correct_answer, exam_id) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('sssssssi', $subject, $questionText, $optionA, $optionB, $optionC, $optionD, $correctAnswer, $examId);
        $stmt->execute();
    }

    $stmt->close();

    // Redirect to admin dashboard
    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Questions</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-black min-h-screen flex flex-col items-center justify-center">

    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-4xl">
        <h1 class="text-2xl font-bold mb-4 text-center">Add Questions</h1>
        <form action="" method="POST" class="space-y-8">
            <?php for ($i = 1; $i <= $totalQuestions; $i++): ?>
                <div class="space-y-4 border-t pt-4">
                    <h2 class="text-lg font-bold">Question <?php echo $i; ?></h2>
                    <div>
                        <label class="block text-sm font-medium">Subject</label>
                        <select name="subject_<?php echo $i; ?>" required class="w-full mt-1 px-4 py-2 border rounded-lg">
                            <option value="DSA">DSA</option>
                            <option value="Java">Java</option>
                            <option value="OOPS">OOPS</option>
                            <option value="Network">Network</option>
                            <option value="DSMA">DSMA</option>
                            <option value="C">C</option>
                            <option value="Aptitude">Aptitude</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Question Text</label>
                        <textarea name="question_<?php echo $i; ?>" required class="w-full mt-1 px-4 py-2 border rounded-lg"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Option A</label>
                            <input type="text" name="option_a_<?php echo $i; ?>" required class="w-full mt-1 px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Option B</label>
                            <input type="text" name="option_b_<?php echo $i; ?>" required class="w-full mt-1 px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Option C</label>
                            <input type="text" name="option_c_<?php echo $i; ?>" required class="w-full mt-1 px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Option D</label>
                            <input type="text" name="option_d_<?php echo $i; ?>" required class="w-full mt-1 px-4 py-2 border rounded-lg">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Correct Answer</label>
                        <select name="correct_answer_<?php echo $i; ?>" required class="w-full mt-1 px-4 py-2 border rounded-lg">
                            <option value="A">Option A</option>
                            <option value="B">Option B</option>
                            <option value="C">Option C</option>
                            <option value="D">Option D</option>
                        </select>
                    </div>
                </div>
            <?php endfor; ?>
            <button type="submit" class="w-full bg-black text-white py-2 rounded-lg hover:bg-gray-800">Submit Questions</button>
        </form>
    </div>

</body>
</html>
