<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

require 'db_connect.php'; // Include database connection

// Handle the deletion of the test
if (isset($_GET['delete_exam_id'])) {
    $examIdToDelete = $_GET['delete_exam_id'];

    // First delete the questions related to the exam
    $deleteQuestionsQuery = "DELETE FROM questions WHERE exam_id = ?";
    $stmt = $conn->prepare($deleteQuestionsQuery);
    $stmt->bind_param('i', $examIdToDelete);
    $stmt->execute();
    $stmt->close();

    // Then delete the exam record from the examDetails table
    $deleteExamQuery = "DELETE FROM examDetails WHERE exam_id = ?";
    $stmt = $conn->prepare($deleteExamQuery);
    $stmt->bind_param('i', $examIdToDelete);
    $stmt->execute();
    $stmt->close();

    // Redirect to the same page to refresh the list
    header("Location: delete_test.php");
    exit;
}

// Fetch all the exams to display
$query = "SELECT * FROM examDetails";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Existing Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-black min-h-screen flex flex-col items-center">

    <!-- Container for delete test -->
    <div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-4xl mt-10">
        <h1 class="text-2xl font-bold mb-4 text-center">Delete Existing Test</h1>

        <?php if ($result->num_rows > 0): ?>
            <table class="w-full table-auto border-collapse border border-gray-300">
                <thead>
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left">Test Name</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Publish Date</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="border-t">
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['mocktest_name']); ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['date_to_publish']); ?></td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="?delete_exam_id=<?php echo $row['exam_id']; ?>" 
                                   class="text-red-600 hover:text-red-800">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-gray-500 mt-4">No tests available to delete.</p>
        <?php endif; ?>
    </div>

</body>
</html>
