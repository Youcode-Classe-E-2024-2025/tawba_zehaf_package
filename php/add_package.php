<?php
require './db.php';

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nomPackage = $_POST['nom_package'];
    $description = $_POST['description'];
    
    // Ajouter le package dans la base de données
    $stmt = $pdo->prepare("INSERT INTO packages (nom_package, description) VALUES (:nom_package, :description)");
    $stmt->execute(['nom_package' => $nomPackage, 'description' => $description]);

    // Récupérer l'ID du package ajouté
    $packageId = $pdo->lastInsertId();

    // Ajouter ou sélectionner un auteur
    if (!empty($_POST['author_id'])) {
        // Si un auteur existant est sélectionné
        $authorId = $_POST['author_id'];
        $stmt = $pdo->prepare("INSERT INTO auteurs_packages (id_auteur, id_package) VALUES (:author_id, :package_id)");
        $stmt->execute(['author_id' => $authorId, 'package_id' => $packageId]);
    } elseif (!empty($_POST['new_author'])) {
        // Si un nouvel auteur est ajouté manuellement
        $newAuthor = $_POST['new_author'];
        $stmt = $pdo->prepare("INSERT INTO auteurs (nom_auteur) VALUES (:new_author)");
        $stmt->execute(['new_author' => $newAuthor]);
        $authorId = $pdo->lastInsertId();
        $stmt = $pdo->prepare("INSERT INTO auteurs_packages (id_auteur, id_package) VALUES (:author_id, :package_id)");
        $stmt->execute(['author_id' => $authorId, 'package_id' => $packageId]);
    }

    // Ajouter ou sélectionner une version
    if (!empty($_POST['version'])) {
        // Si une version existante est sélectionnée
        $version = $_POST['version'];
        $stmt = $pdo->prepare("INSERT INTO versions (id_package, version) VALUES (:package_id, :version)");
        $stmt->execute(['package_id' => $packageId, 'version' => $version]);
    } elseif (!empty($_POST['new_version'])) {
        // Si une nouvelle version est ajoutée manuellement
        $newVersion = $_POST['new_version'];
        $stmt = $pdo->prepare("INSERT INTO versions (id_package, version) VALUES (:package_id, :new_version)");
        $stmt->execute(['package_id' => $packageId, 'new_version' => $newVersion]);
    }

    // Rediriger après l'ajout
    header("Location: user.php");
    exit();
}

// Récupérer la liste des auteurs et des versions depuis la base de données
$stmtAuthors = $pdo->prepare("SELECT * FROM auteurs");
$stmtAuthors->execute();
$authors = $stmtAuthors->fetchAll();

$stmtVersions = $pdo->prepare("SELECT DISTINCT version FROM versions");
$stmtVersions->execute();
$versions = $stmtVersions->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Package</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-indigo-900 text-gray-200 font-sans">

    <header class="bg-teal-600 text-white p-8 text-center shadow-xl rounded-b-lg">
        <h1 class="text-4xl font-semibold">Add New Package</h1>
    </header>

    <div class="container mx-auto p-8 mt-8 bg-gray-850 rounded-lg shadow-xl">
        <form method="POST" class="space-y-6">
            <!-- Nom du package -->
            <div>
                <label for="nom_package" class="text-lg font-semibold text-teal-300">Package Name</label>
                <input type="text" name="nom_package" id="nom_package" class="w-full p-3 rounded-lg bg-gray-700 text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500" required>
            </div>

            <!-- Description du package -->
            <div>
                <label for="description" class="text-lg font-semibold text-teal-300">Description</label>
                <textarea name="description" id="description" class="w-full p-3 rounded-lg bg-gray-700 text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500" rows="4" required></textarea>
            </div>

            <!-- Sélectionner un auteur ou ajouter un auteur manuellement -->
            <div>
                <label for="author_id" class="text-lg font-semibold text-teal-300">Select Author</label>
                <select name="author_id" id="author_id" class="w-full p-3 rounded-lg bg-gray-700 text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">-- Select an Author --</option>
                    <?php foreach ($authors as $author): ?>
                        <option value="<?php echo htmlspecialchars($author['id_auteur'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($author['nom_auteur'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div class="mt-4">
                    <label for="new_author" class="text-lg font-semibold text-teal-300">Or Add New Author</label>
                    <input type="text" name="new_author" id="new_author" class="w-full p-3 rounded-lg bg-gray-700 text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Enter new author name">
                </div>
            </div>

            <!-- Sélectionner une version ou ajouter une version manuellement -->
            <div>
                <label for="version" class="text-lg font-semibold text-teal-300">Select Version</label>
                <select name="version" id="version" class="w-full p-3 rounded-lg bg-gray-700 text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">-- Select a Version --</option>
                    <?php foreach ($versions as $version): ?>
                        <option value="<?php echo htmlspecialchars($version['version'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($version['version'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div class="mt-4">
                    <label for="new_version" class="text-lg font-semibold text-teal-300">Or Add New Version</label>
                    <input type="text" name="new_version" id="new_version" class="w-full p-3 rounded-lg bg-gray-700 text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Enter new version">
                </div>
            </div>

            <!-- Bouton pour soumettre le formulaire -->
            <button type="submit" class="bg-teal-600 text-white py-3 px-6 rounded-lg hover:bg-teal-700 transition">
                Add Package
            </button>
        </form>
    </div>

</body>

</html>
