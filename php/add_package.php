<?php
require './db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input values from the form
    $nom_package = $_POST['nom_package'] ?? '';
    $description = $_POST['description'] ?? '';
    $version = $_POST['version'] ?? '';
    $release_date = $_POST['release_date'] ?? '';  // Added release date
    $authors = $_POST['authors'] ?? '';
    $emails = $_POST['emails'] ?? ''; // Added email input for authors

    // Validate input
    if (empty($nom_package) || empty($description) || empty($version) || empty($release_date) || empty($authors) || empty($emails)) {
        $error = "All fields are required!";
    } else {
        try {
            // Begin a transaction
            $pdo->beginTransaction();

            // Insert the package into the 'packages' table
            $stmt = $pdo->prepare("INSERT INTO packages (nom_package, description) VALUES (:nom_package, :description)");
            $stmt->execute(['nom_package' => $nom_package, 'description' => $description]);

            // Get the last inserted package ID
            $id_package = $pdo->lastInsertId();

            // Insert version into 'versions' table
            $stmt = $pdo->prepare("INSERT INTO versions (id_package, version, date_release) VALUES (:id_package, :version, :date_release)");
            $stmt->execute(['id_package' => $id_package, 'version' => $version, 'date_release' => $release_date]);

            // Insert authors and their emails into 'auteurs' and 'auteurs_packages' tables
            $authors = explode(',', $authors);  // Expect authors to be comma-separated
            $emails = explode(',', $emails);    // Expect emails to be comma-separated

            foreach ($authors as $key => $author_name) {
                $author_name = trim($author_name);
                $email = isset($emails[$key]) ? trim($emails[$key]) : '';

                // Insert the author into the 'auteurs' table if they don't exist
                $stmt = $pdo->prepare("INSERT INTO auteurs (nom_auteur, email) VALUES (:nom_auteur, :email) ON DUPLICATE KEY UPDATE id_auteur=LAST_INSERT_ID(id_auteur)");
                $stmt->execute(['nom_auteur' => $author_name, 'email' => $email]);

                // Get the author ID and link to the package
                $author_id = $pdo->lastInsertId();
                $stmt = $pdo->prepare("INSERT INTO auteurs_packages (id_package, id_auteur) VALUES (:id_package, :id_auteur)");
                $stmt->execute(['id_package' => $id_package, 'id_auteur' => $author_id]);
            }

            // Commit the transaction
            $pdo->commit();

            // Redirect to the user page after successful addition
            header("Location: user.php");
            exit;
        } catch (Exception $e) {
            // Rollback if there is an error
            $pdo->rollBack();
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Package</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-indigo-900 text-gray-200 font-sans">
    <header class="bg-teal-600 text-white p-8 text-center shadow-xl rounded-b-lg">
        <h1 class="text-4xl font-semibold">Add New Package</h1>
    </header>

    <div class="container mx-auto p-8 mt-8 bg-gray-850 rounded-lg shadow-xl">
        <div class="flex justify-center mb-6">
            <a href="user.php" class="bg-gray-500 text-white py-3 px-6 rounded-full shadow-md hover:bg-gray-600 transition">Back to Dashboard</a>
        </div>

        <?php if (!empty($error)): ?>
            <div class="text-red-500 text-center mb-6"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <!-- Add Package Form -->
        <form method="POST" action="add_package.php" class="space-y-6">
            <div>
                <label for="nom_package" class="block text-lg font-semibold">Package Name</label>
                <input type="text" id="nom_package" name="nom_package" class="w-full p-3 rounded-lg bg-gray-700 text-gray-300" required>
            </div>
            <div>
                <label for="description" class="block text-lg font-semibold">Description</label>
                <textarea id="description" name="description" class="w-full p-3 rounded-lg bg-gray-700 text-gray-300" required></textarea>
            </div>
            <div>
                <label for="version" class="block text-lg font-semibold">Version</label>
                <input type="text" id="version" name="version" class="w-full p-3 rounded-lg bg-gray-700 text-gray-300" required>
            </div>
            <div>
                <label for="release_date" class="block text-lg font-semibold">Release Date</label>
                <input type="date" id="release_date" name="release_date" class="w-full p-3 rounded-lg bg-gray-700 text-gray-300" required>
            </div>
            <div>
                <label for="authors" class="block text-lg font-semibold">Authors (comma-separated)</label>
                <input type="text" id="authors" name="authors" class="w-full p-3 rounded-lg bg-gray-700 text-gray-300" required>
            </div>
            <div>
                <label for="emails" class="block text-lg font-semibold">Emails of Authors (comma-separated)</label>
                <input type="text" id="emails" name="emails" class="w-full p-3 rounded-lg bg-gray-700 text-gray-300" required>
            </div>
            <button type="submit" class="w-full py-3 px-6 bg-teal-600 text-white rounded-lg shadow-md hover:bg-teal-700 transition">Add Package</button>
        </form>
    </div>

    <footer class="bg-teal-600 text-white text-center py-5 mt-8">
        <p>&copy; 2024 Package Management System. All Rights Reserved.</p>
    </footer>
</body>

</html>
