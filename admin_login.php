<?php
session_start();
include 'db_connect.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if the admin exists
    $query = "SELECT * FROM admin WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if ($password === $admin['password']) { // Directly compare passwords
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['name'];
            header("Location: admin_dashboard.php"); // Redirect to the dashboard
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-black min-h-screen flex items-center justify-center">
    <!-- Navbar -->
    <nav class="absolute top-0 left-0 right-0 bg-gray-100 shadow-md py-4 px-6 flex justify-between items-center z-10">
        <div class="text-2xl font-bold">MockTestWebsite</div>
    </nav>

    <!-- Main Content -->
    <div class="flex flex-wrap w-full h-full items-center justify-center">
        <!-- Hero Section -->
        <div class="w-full lg:w-1/2 bg-gradient-to-r from-gray-200 via-gray-300 to-gray-400 flex items-center justify-center py-10 lg:py-0">
            <h1 class="text-5xl font-extrabold text-gray-700 opacity-30">MockTest Admin Panel</h1>
        </div>

        <!-- Login Card -->
        <div class="w-full lg:w-1/2 flex justify-center items-center p-6">
            <div class="relative bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
                <h2 class="text-2xl font-bold text-center mb-6">Admin Login</h2>
                <?php if (isset($error)): ?>
                    <div class="bg-red-500 text-white px-4 py-2 mb-4 rounded">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <form action="admin_login.php" method="POST" class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" class="w-full border border-gray-300 bg-gray-50 text-black rounded-md p-2 focus:outline-none focus:ring focus:ring-gray-300" required>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password" class="w-full border border-gray-300 bg-gray-50 text-black rounded-md p-2 focus:outline-none focus:ring focus:ring-gray-300" required>
                    </div>
                    <button type="submit" class="w-full bg-black text-white py-2 rounded-md hover:bg-gray-800 transition">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
