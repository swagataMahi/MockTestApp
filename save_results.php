<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    die('User not logged in');
}

include('db_connect.php');

// Get the data from POST request
$userName = $_POST['user_name'];
$marks = $_POST['marks'];

// Insert the result into the database
$query = "INSERT INTO results (user_name, marks) VALUES ('$userName', '$marks')";
if ($conn->query($query) === TRUE) {
    echo "Result saved successfully";
} else {
    echo "Error: " . $conn->error;
}
?>
