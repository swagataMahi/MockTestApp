<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$answers = json_encode($_POST['answers']);
$userName = $_POST['user_name'];

// Include the database connection file
include('db_connect.php');

// Insert result into the database
$query = "INSERT INTO exam_results (user_name, answers, exam_date) VALUES (?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $userName, $answers);
$stmt->execute();
$stmt->close();

echo 'Success';
?>
