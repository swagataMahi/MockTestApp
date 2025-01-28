<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$adminName = $_SESSION['admin_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-black min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-gray-100 shadow-md py-4 px-6 flex justify-between items-center">
        <div class="text-2xl font-bold">MockTestWebsite</div>
        <div class="text-lg">Welcome, <span class="font-semibold"><?php echo $adminName; ?></span></div>
    </nav>

    <!-- Main Content -->
    <div class="flex flex-col items-center justify-start flex-grow p-8 space-y-8">
        <!-- Admin Name -->
        <h1 class="text-4xl font-bold">Admin Dashboard</h1>

        <!-- Options Section -->
        <div class="w-full max-w-3xl grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Create Test Button -->
            <a href="create_test.php" class="block bg-white shadow-lg rounded-lg p-6 text-center border hover:shadow-xl transition">
                <h2 class="text-2xl font-bold mb-2">Create Test</h2>
                <p class="text-gray-600">Create a new mock test for students.</p>
            </a>

            <!-- Delete Existing Tests Button -->
            <a href="delete_test.php" class="block bg-white shadow-lg rounded-lg p-6 text-center border hover:shadow-xl transition">
                <h2 class="text-2xl font-bold mb-2">Delete Existing Tests</h2>
                <p class="text-gray-600">Manage and delete previously created tests.</p>
            </a>
        </div>
    </div>

</body>
</html>
