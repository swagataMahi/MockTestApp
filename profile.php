<?php
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : null;

if (!$isLoggedIn) {
    header('Location: index.php'); // Redirect if not logged in
    exit();
}

// Include the database connection file
include('db_connect.php');

// Fetch user details from the 'users' table using the user name (which is in session)
$queryUser = "SELECT * FROM users WHERE name = '$userName'";
$resultUser = $conn->query($queryUser);

if ($resultUser->num_rows > 0) {
    $user = $resultUser->fetch_assoc();
} else {
    echo "User not found";
    exit();
}

// Fetch results for the user from the 'results' table using the 'user_name'
$queryResults = "SELECT * FROM results WHERE user_name = '" . $user['name'] . "'";
$resultResults = $conn->query($queryResults);
$results = $resultResults->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-black font-sans">

    <div class="flex justify-center items-center min-h-screen">
        <div class="w-full max-w-2xl bg-white text-black p-8 rounded-lg shadow-lg">
            <h1 class="text-3xl font-bold mb-6">User Profile</h1>

            <!-- User Info -->
            <div class="mb-6">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            </div>

            <!-- Results Section -->
            <h2 class="text-2xl font-semibold mb-4">Your Results</h2>

            <?php if ($resultResults->num_rows > 0): ?>
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="px-4 py-2 text-left">Test Name</th>
                            <th class="px-4 py-2 text-left">Marks</th>
                            <th class="px-4 py-2 text-left">Exam Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $result): ?>
                            <tr class="border-b">
                                <td class="px-4 py-2"><?php echo htmlspecialchars($result['user_name']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($result['marks']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($result['exam_date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No results found for this user.</p>
            <?php endif; ?>
            
            <!-- Back Button -->
            <div class="mt-6 text-center">
                <a href="index.php" class="bg-blue-500 text-white py-2 px-4 rounded-md">Back to Home</a>
            </div>
        </div>
    </div>

</body>
</html>
