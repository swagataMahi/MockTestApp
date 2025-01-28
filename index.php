<?php
session_start(); // Start session for session-based login

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mock Test Website</title>
    <style>
        /* Ensure body and html take up full height */
        html, body {
            height: 100%;
            margin: 0;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-white text-black font-sans h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="flex justify-between items-center px-6 py-4 border-b border-gray-300">
        <div class="text-2xl font-bold">Mock Test Website</div>
        <div class="flex space-x-4">
            <!-- Check if the user is logged in -->
            <?php if ($isLoggedIn): ?>
                <!-- Profile Dropdown -->
                <div class="relative">
                    <button id="profile-btn" class="flex items-center space-x-2 bg-gray-200 text-black px-4 py-2 rounded-md hover:bg-gray-300 transition">
                        <img src="https://via.placeholder.com/40" alt="Profile" class="w-8 h-8 rounded-full">
                        <span><?php echo htmlspecialchars($userName); ?></span>
                    </button>
                    <!-- Dropdown Menu -->
                    <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg">
                        <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="#" id="signin-btn" class="text-black hover:text-gray-700 transition">Sign In</a>
                <a href="#" id="signup-btn" class="text-black hover:text-gray-700 transition">Sign Up</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="flex flex-col items-center justify-center h-[calc(100vh-4rem)] text-center">
        <h1 class="text-4xl md:text-6xl font-extrabold mb-4">Ace Your Tests</h1>
        <p class="text-lg md:text-xl text-gray-600 mb-8">Your gateway to mastering mock tests</p>
        <button id="giveTestButton" class="bg-black text-white px-8 py-3 rounded-md text-lg hover:bg-gray-800 transition">Give Test</button>

    </section>


<!-- Modal for Instructions -->
<div id="instructionsModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg p-6 w-96">
        <h2 class="text-xl font-bold mb-4">Exam Instructions</h2>
        <div class="mb-4">
            <ul class="list-disc pl-5">
                <li>Each question is worth 1 mark.</li>
                <li>There is negative marking of 1/4 mark for every wrong answer.</li>
                <li>The total time for the test is 10 minutes.</li>
                <li>You must attempt all the questions in the given time.</li>
                <li>Please do not refresh the page during the test.</li>
                <li>Once you click on "Start", the test will begin.</li>
            </ul>
        </div>
        <div class="mb-4">
            <label for="understoodCheckbox" class="flex items-center">
                <input type="checkbox" id="understoodCheckbox" class="mr-2">
                <span>I understood the instructions</span>
            </label>
        </div>
        <div class="flex justify-between">
            <button id="startTestButton" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 disabled:opacity-50" disabled>
                Start Test
            </button>
            <button id="closeModalButton" class="px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400">Close</button>
        </div>
    </div>
</div>

    <!-- Sign Up Modal -->
    <div id="signup-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h2 class="text-2xl font-bold mb-4">Sign Up</h2>
            <form id="signup-form">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium">Name</label>
                    <input type="text" id="name" name="name" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <button type="submit" class="w-full bg-black text-white py-2 rounded-md hover:bg-gray-800 transition">Register</button>
            </form>
            <button id="close-modal" class="mt-4 w-full bg-gray-300 py-2 rounded-md hover:bg-gray-400 transition">Close</button>
        </div>
    </div>

    <!-- Sign In Modal -->
    <div id="signin-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h2 class="text-2xl font-bold mb-4">Sign In</h2>
            <form id="signin-form">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <button type="submit" class="w-full bg-black text-white py-2 rounded-md hover:bg-gray-800 transition">Sign In</button>
            </form>
            <button id="close-signin-modal" class="mt-4 w-full bg-gray-300 py-2 rounded-md hover:bg-gray-400 transition">Close</button>
        </div>
    </div>

    <!-- jQuery for Modal Toggle -->
    <script>
        $(document).ready(function () {
            // Toggle Profile Dropdown
            $('#profile-btn').click(function () {
                $('#profile-dropdown').toggleClass('hidden');
            });

            // Hide dropdown if clicking outside
            $(document).click(function (e) {
                if (!$(e.target).closest('#profile-btn').length) {
                    $('#profile-dropdown').addClass('hidden');
                }
            });

            // Show Sign Up Modal
            $('#signup-btn').click(function () {
                $('#signup-modal').removeClass('hidden').addClass('flex');
            });

            // Hide Sign Up Modal
            $('#close-modal').click(function () {
                $('#signup-modal').addClass('hidden').removeClass('flex');
            });

            // AJAX for Sign Up
            $('#signup-form').submit(function (e) {
                e.preventDefault();
                const name = $('#name').val();
                const email = $('#email').val();
                const password = $('#password').val();

                if (!name || !email || !password) {
                    alert('Please fill in all fields.');
                    return;
                }

                $.ajax({
                    url: 'register.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        alert(response);
                        $('#signup-modal').addClass('hidden').removeClass('flex');
                        $('#signup-form')[0].reset();
                    },
                    error: function () {
                        alert('Error while registering. Please try again.');
                    }
                });
            });

            // Show Sign In Modal
            $('#signin-btn').click(function () {
                $('#signin-modal').removeClass('hidden').addClass('flex');
            });

            // Hide Sign In Modal
            $('#close-signin-modal').click(function () {
                $('#signin-modal').addClass('hidden').removeClass('flex');
            });

            // AJAX for Sign In
            $('#signin-form').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'signin.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        alert(response);
                        if (response === "Login successful!") {
                            location.reload(); // Reload the page to update navbar
                        }
                    },
                    error: function () {
                        alert('Error while signing in. Please try again.');
                    }
                });
            });


            // Get elements
    const giveTestButton = document.getElementById("giveTestButton");
    const instructionsModal = document.getElementById("instructionsModal");
    const closeModalButton = document.getElementById("closeModalButton");
    const startTestButton = document.getElementById("startTestButton");
    const understoodCheckbox = document.getElementById("understoodCheckbox");

    // Show the modal when "Give Test" button is clicked
    giveTestButton.addEventListener("click", () => {
        instructionsModal.classList.remove("hidden");  // Show modal
    });

    // Close the modal when "Close" button is clicked
    closeModalButton.addEventListener("click", () => {
        instructionsModal.classList.add("hidden");  // Hide modal
    });

    // Enable the "Start Test" button when checkbox is checked
    understoodCheckbox.addEventListener("change", () => {
        if (understoodCheckbox.checked) {
            startTestButton.disabled = false;
        } else {
            startTestButton.disabled = true;
        }
    });

    // Redirect to the question page when "Start Test" button is clicked
    startTestButton.addEventListener("click", () => {
        window.location.href = "question.php";  // Redirect to the test page
    });
        });
    </script>
</body>
</html>
