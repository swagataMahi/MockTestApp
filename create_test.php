<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

require 'db_connect.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $testName = $_POST['test_name'];
    $totalQuestions = (int) $_POST['total_questions'];

    // Insert test details
    $stmt = $conn->prepare("INSERT INTO examDetails (mocktest_name, date_to_publish, exam_setter_name) VALUES (?, NOW(), ?)");
    $stmt->bind_param('ss', $testName, $_SESSION['admin_name']);
    $stmt->execute();

    $examId = $conn->insert_id; // Get the inserted exam ID
    $stmt->close();

    // Redirect to add questions
    header("Location: add_questions.php?exam_id=$examId&total_questions=$totalQuestions");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-black min-h-screen flex flex-col items-center justify-center">

    <!-- Form -->
    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4 text-center">Create a New Test</h1>
        <form action="" method="POST" class="space-y-4">
            <div>
                <label for="test_name" class="block text-sm font-medium">Test Name</label>
                <input type="text" id="test_name" name="test_name" required class="w-full mt-1 px-4 py-2 border rounded-lg">
            </div>
            <div>
                <label for="total_questions" class="block text-sm font-medium">Total Questions</label>
                <input type="number" id="total_questions" name="total_questions" min="1" required class="w-full mt-1 px-4 py-2 border rounded-lg">
            </div>
            <button type="submit" class="w-full bg-black text-white py-2 rounded-lg hover:bg-gray-800">Next</button>
        </form>
    </div>

</body>
</html>
