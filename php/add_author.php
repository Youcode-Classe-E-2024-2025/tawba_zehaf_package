<?php
require './db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si un nouvel auteur est ajouté
    if (isset($_POST['new_author']) && !empty($_POST['new_author']) && isset($_POST['email']) && !empty($_POST['email'])) {
        $newAuthor = $_POST['new_author'];
        $email = $_POST['email'];

        // Vérifier si l'email existe déjà dans la base de données
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM auteurs WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $emailExists = $stmt->fetchColumn();

        if ($emailExists) {
            // L'email existe déjà, afficher un message d'erreur
            echo "This email address is already in use. Please choose another email.";
        } else {
            // Insérer le nouvel auteur dans la base de données
            $stmt = $pdo->prepare("INSERT INTO auteurs (nom_auteur, email) VALUES (:nom_auteur, :email)");
            $stmt->execute(['nom_auteur' => $newAuthor, 'email' => $email]);

            // Récupérer l'ID du nouvel auteur
            $authorId = $pdo->lastInsertId();

            // Ajouter l'auteur au package (ou effectuer la logique nécessaire)
            $packageId = 1;  // Remplacez par l'ID réel du package
            $stmt = $pdo->prepare("INSERT INTO auteurs_packages (id_auteur, id_package) VALUES (:author_id, :package_id)");
            $stmt->execute(['author_id' => $authorId, 'package_id' => $packageId]);

            // Rediriger après l'ajout
            header("Location: user.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Author</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-indigo-900 text-gray-200 font-sans">

    <header class="bg-teal-600 text-white p-8 text-center shadow-xl rounded-b-lg">
        <h1 class="text-4xl font-semibold">Add New Author</h1>
    </header>

    <div class="container mx-auto p-8 mt-8 bg-gray-850 rounded-lg shadow-xl">
        <form method="POST" class="space-y-4">
            <!-- Champ pour ajouter un nouvel auteur -->
            <label for="new_author" class="text-lg font-semibold text-teal-300">Author Name</label>
            <input type="text" name="new_author" id="new_author" class="w-full p-3 rounded-lg bg-gray-700 text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Enter new author name" required>

            <!-- Champ pour l'email du nouvel auteur -->
            <label for="email" class="text-lg font-semibold text-teal-300">Email</label>
            <input type="email" name="email" id="email" class="w-full p-3 rounded-lg bg-gray-700 text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Enter email" required>

            <!-- Bouton pour soumettre le formulaire -->
            <button type="submit" class="bg-teal-600 text-white py-3 px-6 rounded-lg hover:bg-teal-700 transition">
                Add Author
            </button>
        </form>
    </div>

</body>

</html>
