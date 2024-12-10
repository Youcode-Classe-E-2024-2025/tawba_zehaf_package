<?php
session_start();
require './db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $pdo->prepare("SELECT * FROM Users WHERE username = :username AND password = :password");
    $query->execute(['username' => $username, 'password' => $password]);
    $user = $query->fetch();

    if ($user) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: user.php');
        }
    } else {
        $error = "cest invalid!";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../../css/output.css">
</head>

<body class="bg-primary text-white font-jura">
    <div class="container mx-auto p-6">
        <!-- Login Section -->
        <div class="flex justify-center items-center">
            <!-- Illustration -->
            <div class="w-1/3 hidden lg:block">

            </div>
            <!-- Form -->
            <div id="loginFormSection" class="bg-black p-10 rounded-lg shadow-md w-full max-w-md">
                <h2 class="text-3xl font-bold mb-6 text-center">Login Page</h2>

                <form id="loginForm">
                    <input type="email" id="email" placeholder="Your Email"
                        class="w-full p-3 mb-4 rounded bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <input type="password" id="password" placeholder="Password"
                        class="w-full p-3 mb-4 rounded bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <div class="text-right mb-4">
                        <a href="#" id="forgotPasswordLink" class="text-blue-500 hover:underline">Forgot Password?</a>
                    </div>
                    <div class="flex items-center mb-4">
                        <input type="checkbox" id="terms" class="mr-2">
                        <label for="terms" class="text-sm">
                            You agree to our <a href="#" class="text-blue-500 hover:underline">Privacy Policy</a> and <a
                                href="#" class="text-blue-500 hover:underline">Terms of Service</a>.
                        </label>
                    </div>
                    <a id="sub_but" href="">
                        <button type="submit"
                        class="w-full bg-red-500 py-3 rounded text-lg font-semibold hover:bg-red-600">Login</button>
                    </a >
                </form>
            </div>
            <!-- Reset Password Section (hidden initially) -->
            <div id="resetPasswordSection" class="bg-black p-10 rounded-lg shadow-md w-full max-w-md hidden">
                <h2 class="text-3xl font-bold mb-6 text-center">Reset Your Password</h2>

                <form id="resetPasswordForm">
                    <input type="email" id="resetEmail" placeholder="Enter Your Email"
                        class="w-full p-3 mb-4 rounded bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <input type="password" id="newPassword" placeholder="New Password"
                        class="w-full p-3 mb-4 rounded bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <input type="password" id="confirmNewPassword" placeholder="Confirm Your New Password"
                        class="w-full p-3 mb-4 rounded bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <button type="submit"
                        class="w-full bg-green-500 py-3 rounded text-lg font-semibold hover:bg-green-600">Reset
                        Password</button>
                </form>

                <div class="text-center mt-4">
                    <a href="#" id="backToLoginLink" class="text-blue-500 hover:underline">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
   </html>