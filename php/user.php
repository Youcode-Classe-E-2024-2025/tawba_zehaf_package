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

// Output or render your packages here
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-gray-100 font-sans">

    <header class="bg-blue-600 text-white p-6 text-center shadow-lg">
        <h1 class="text-3xl font-bold">Welcome, User!</h1>
    </header>

    <div class="container mx-auto p-6 mt-6 bg-gray-800 rounded-lg shadow-lg">
        <a href="logout.php" class="block text-center text-white bg-red-600 py-2 px-4 rounded-lg mb-4 hover:bg-red-700 transition">Logout</a>
        
        <!-- Search Bar -->
        <div class="flex justify-center mb-6">
            <form method="GET" action="user.php" class="flex">
                <input type="text" name="query" placeholder="Search packages"
                    class="w-80 p-3 rounded-l-lg bg-gray-700 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="<?php echo htmlspecialchars($query ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit" class="p-3 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 transition">Search</button>
            </form>
        </div>

        <!-- Add Package Button -->
        <div class="flex justify-end mb-6">
            <a href="add_package.php" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">Add Package</a>
        </div>

        <h2 class="text-xl font-semibold mb-4">Packages</h2>
        
        <!-- Package List -->
        <ul class="space-y-4">
            <?php foreach ($packages as $package): ?>
                <li class="bg-gray-700 p-6 rounded-lg shadow-md hover:shadow-xl transition">
                    <h3 class="text-2xl font-semibold"><?php echo htmlspecialchars($package['nom_package'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h3>
                    <em class="text-sm text-gray-400">Version: <?php echo htmlspecialchars($package['version'] ?? '', ENT_QUOTES, 'UTF-8'); ?></em><br>
                    <em class="text-sm text-gray-400">Authors: <?php echo htmlspecialchars($package['authors'] ?? '', ENT_QUOTES, 'UTF-8'); ?></em><br>
                    <p class="mt-4"><?php echo nl2br(htmlspecialchars($package['description'] ?? '', ENT_QUOTES, 'UTF-8')); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <footer class="bg-blue-600 text-white text-center py-4 mt-6">
        <p>&copy; 2024 Package Management System</p>
    </footer>

</body>

</html>

