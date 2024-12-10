<?php
require './db.php';
// Search functionality
$query = $_GET['query'] ?? '';

// Fetch packages with their version and authors

$stmt = $pdo->prepare("
    SELECT p.id_packages, p.name AS nom_package, p.description, 
           MAX(v.version_number) AS version, GROUP_CONCAT(a.name) AS authors
    FROM packages p
    LEFT JOIN versions v ON p.id_packages = v.package_id
    LEFT JOIN authors a ON p.author_id = a.id_authors
    WHERE p.name LIKE :query OR p.description LIKE :query
    GROUP BY p.id_packages, p.name, p.description
    ORDER BY p.name
");
$stmt->execute(['query' => "%$query%"]);
$packages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Packages JS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-blue-600 text-white py-4">
        <h1 class="text-center text-2xl font-bold">Gestion des Packages JS</h1>
    </header>

    <main class="container mx-auto mt-8">
        <!-- Formulaire pour ajouter ou modifier un package -->
        <section class="bg-white p-6 shadow-md rounded-lg mb-6">
            <h2 class="text-lg font-semibold mb-4"><?php echo $isEditing ? 'Modifier' : 'Ajouter'; ?> un package</h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="hidden" name="editing_index" value="<?php echo $isEditing ? $editingIndex : ''; ?>">
                <div>
                    <label for="package_name" class="block text-sm font-medium text-gray-700">Nom du Package</label>
                    <input type="text" id="package_name" name="package_name" value="<?php echo $name; ?>" class="mt-1 p-2 border border-gray-300 rounded-md w-full" required>
                </div>
                <div>
                    <label for="package_author" class="block text-sm font-medium text-gray-700">Auteur</label>
                    <input type="text" id="package_author" name="package_author" value="<?php echo $author; ?>" class="mt-1 p-2 border border-gray-300 rounded-md w-full" required>
                </div>
                <div>
                    <label for="package_version" class="block text-sm font-medium text-gray-700">Version</label>
                    <input type="text" id="package_version" name="package_version" value="<?php echo $version; ?>" class="mt-1 p-2 border border-gray-300 rounded-md w-full" required>
                </div>
                <div class="col-span-3">
                    <button type="submit" name="add_or_update" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"><?php echo $isEditing ? 'Modifier' : 'Ajouter'; ?></button>
                    <?php if ($isEditing): ?>
                        <a href="php\packages.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Annuler</a>
                    <?php endif; ?>
                </div>
            </form>
        </section>

        <!-- Liste des packages -->
        <section class="bg-white p-6 shadow-md rounded-lg">
            <h2 class="text-lg font-semibold mb-4">Liste des Packages</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-gray-50 border border-gray-200 rounded-lg">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nom</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Auteur</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Version</th>
                            <th class="py-3 px-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['packages'] as $index => $package): ?>
                            <tr>
                                <td class="py-3 px-4 border-b"><?php echo htmlspecialchars($package['name']); ?></td>
                                <td class="py-3 px-4 border-b"><?php echo htmlspecialchars($package['author']); ?></td>
                                <td class="py-3 px-4 border-b"><?php echo htmlspecialchars($package['version']); ?></td>
                                <td class="py-3 px-4 border-b text-right">
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                                        <button type="submit" name="edit" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">Modifier</button>
                                    </form>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                                        <button type="submit" name="delete" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
