<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form input values
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare query to fetch the user with the given username
    $query = $pdo->prepare("SELECT * FROM Users WHERE username = :username");
    $query->execute(['username' => $username]);
    $user = $query->fetch();

    // Check if user exists and verify password
    if ($user && password_verify($password, $user['password'])) {
        // Store session variables
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect to the appropriate page based on user role
        if ($user['role'] === 'admin') {
            header('Location: admin.php');
            exit;
        } else {
            header('Location: user.php');
            exit;
        }
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-primary text-white font-jura">
    <div class="container mx-auto p-6">
        <!-- Login Section -->
        <div class="flex justify-center items-center">
            <!-- Illustration (Optional) -->
            <div class="w-1/3 hidden lg:block">
                <!-- You can add an illustration here -->
            </div>
            <!-- Form -->
            <div id="loginFormSection" class="bg-black p-10 rounded-lg shadow-md w-full max-w-md">
                <h2 class="text-3xl font-bold mb-6 text-center">Login Page</h2>

                <?php if (isset($error)): ?>
                    <div class="text-red-500 text-center mb-4"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <input type="text" name="username" placeholder="Your Username"
                        class="w-full p-3 mb-4 rounded bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500" required>

                    <input type="password" name="password" placeholder="Password"
                        class="w-full p-3 mb-4 rounded bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500" required>

                    <div class="flex items-center mb-4">
                        <input type="checkbox" id="terms" class="mr-2">
                        <label for="terms" class="text-sm">
                            You agree to our <a href="#" class="text-blue-500 hover:underline">Privacy Policy</a> and <a
                                href="#" class="text-blue-500 hover:underline">Terms of Service</a>.
                        </label>
                    </div>
                    <button type="submit"
                        class="w-full bg-red-500 py-3 rounded text-lg font-semibold hover:bg-red-600">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
