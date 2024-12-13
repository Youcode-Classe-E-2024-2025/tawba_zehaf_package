<?php
require './db.php';
// Search functionality
$query = $_GET['query'] ?? '';
$stmt = $pdo->prepare("
    SELECT p.id_package, p.nom_package, p.description, 
           MAX(v.version) AS version, GROUP_CONCAT(a.nom_auteur) AS authors
    FROM packages p
    LEFT JOIN versions v ON p.id_package = v.id_package
    LEFT JOIN auteurs_packages ap ON p.id_package = ap.id_package
    LEFT JOIN auteurs a ON ap.id_auteur = a.id_auteur
    WHERE p.nom_package LIKE :query OR p.description LIKE :query
    GROUP BY p.id_package, p.nom_package, p.description
    ORDER BY p.nom_package
");

$stmt->execute(['query' => "%$query%"]);
$packages = $stmt->fetchAll();

// Requête pour obtenir le total des packages
$stmtTotalPackages = $pdo->prepare("SELECT COUNT(*) AS total FROM packages");
$stmtTotalPackages->execute();
$totalPackages = $stmtTotalPackages->fetchColumn();

// Requête pour obtenir le total des auteurs
$stmtTotalAuthors = $pdo->prepare("SELECT COUNT(DISTINCT a.id_auteur) AS total FROM auteurs a");
$stmtTotalAuthors->execute();
$totalAuthors = $stmtTotalAuthors->fetchColumn();

// Requête pour obtenir le total des versions
$stmtTotalVersions = $pdo->prepare("SELECT COUNT(*) AS total FROM versions");
$stmtTotalVersions->execute();
$totalVersions = $stmtTotalVersions->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Ensure smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Fade-in effect */
        .fade-in {
            opacity: 0;
            transition: opacity 1s ease-in;
        }

        .fade-in.visible {
            opacity: 1;
        }

        /* Hover animation */
        .scale-hover:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease-in-out;
        }

        .rotate-hover:hover {
            transform: rotate(3deg);
            transition: transform 0.3s ease-in-out;
        }
    </style>
</head>

<body class="bg-indigo-900 text-gray-200 font-sans">

    <header class="bg-teal-600 text-white p-8 text-center shadow-xl rounded-b-lg">
        <h1 class="text-4xl font-semibold">Hello, User!</h1>
    </header>

    <div class="container mx-auto p-8 mt-8 bg-gray-850 rounded-lg shadow-xl fade-in">
        <!-- Logout Button -->
        <div class="flex justify-center mb-6">
            <a href="logout.php"
                class="bg-red-500 text-white py-3 px-6 rounded-full shadow-md hover:bg-red-600 transition transform hover:scale-105 scale-hover">Logout</a>
        </div>

        <!-- Search Bar -->
        <div class="flex justify-center mb-8">
            <form method="GET" action="user.php" class="flex space-x-3">
                <input type="text" name="query" placeholder="Search for packages"
                    class="w-72 p-3 rounded-lg bg-gray-700 text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500 transition"
                    value="<?php echo htmlspecialchars($query ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit" class="p-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">Search</button>
            </form>
        </div>
<!-- Add Package, Add Author, and Add Version Buttons -->
<div class="flex justify-start mb-6 space-x-4">
    <!-- Add Package Button -->
    <a href="add_package.php"
        class="bg-yellow-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-yellow-600 transition transform hover:scale-105 rotate-hover">Add
        New Package</a>

    <!-- Add Author Button -->
    <a href="add_author.php"
        class="bg-teal-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-teal-600 transition transform hover:scale-105 rotate-hover">Add
        New Author</a>

    <!-- Add Version Button -->
    <a href="add_version.php"
        class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-blue-600 transition transform hover:scale-105 rotate-hover">Add
        New Version</a>
</div>

        <!-- Add Package Button -->
        <!-- <div class="flex justify-start mb-6">
            <a href="add_package.php"
                class="bg-yellow-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-yellow-600 transition transform hover:scale-105 rotate-hover">Add
                New Package</a>
        </div> -->

        <h2 class="text-2xl font-semibold text-center mb-4">Available Packages</h2>

        <!-- Total Packages, Authors, and Versions -->
        <div class="text-center mb-6">
            <p class="text-lg font-semibold text-teal-300">Total Packages: <?php echo htmlspecialchars($totalPackages, ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="text-lg font-semibold text-teal-300">Total Authors: <?php echo htmlspecialchars($totalAuthors, ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="text-lg font-semibold text-teal-300">Total Versions: <?php echo htmlspecialchars($totalVersions, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <!-- Package List -->
        <ul class="space-y-6">
            <?php foreach ($packages as $package): ?>
            <li class="bg-gray-750 p-6 rounded-lg shadow-lg hover:shadow-2xl transition transform hover:scale-102 fade-in">
                <h3 class="text-3xl font-bold text-teal-300"><?php echo htmlspecialchars($package['nom_package'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h3>
                <em class="text-sm text-teal-400">Version: <?php echo htmlspecialchars($package['version'] ?? '', ENT_QUOTES, 'UTF-8'); ?></em><br>
                <em class="text-sm text-teal-400">Authors: <?php echo htmlspecialchars($package['authors'] ?? '', ENT_QUOTES, 'UTF-8'); ?></em><br>
                <p class="mt-4 text-gray-300"><?php echo nl2br(htmlspecialchars($package['description'] ?? '', ENT_QUOTES, 'UTF-8')); ?></p>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <footer class="bg-teal-600 text-white text-center py-5 mt-8">
        <p>&copy; 2024 Package Management System. All Rights Reserved.</p>
    </footer>

    <script>
        // JavaScript to add the fade-in effect when the page loads
        document.addEventListener('DOMContentLoaded', function () {
            const fadeInElements = document.querySelectorAll('.fade-in');
            fadeInElements.forEach(function (element) {
                element.classList.add('visible');
            });
        });

        // Function to show or hide elements based on scroll position (optional)
        window.addEventListener('scroll', function () {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach(function (element) {
                const position = element.getBoundingClientRect();
                if (position.top < window.innerHeight && position.bottom >= 0) {
                    element.classList.add('visible');
                }
            });
        });
    </script>

</body>

</html>