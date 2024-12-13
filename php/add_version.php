<?php
require './db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $packageId = $_POST['package_id'];
    $version = $_POST['version'];

    // Add the version to the package
    $stmt = $pdo->prepare("INSERT INTO versions (id_package, version) VALUES (:package_id, :version)");
    $stmt->execute(['package_id' => $packageId, 'version' => $version]);

    header("Location: user.php");
    exit();
}

// Get the list of packages
$stmt = $pdo->prepare("SELECT * FROM packages");
$stmt->execute();
$packages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Version</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-indigo-900 text-gray-200 font-sans">

    <header class="bg-teal-600 text-white p-8 text-center shadow-xl rounded-b-lg">
        <h1 class="text-4xl font-semibold">Add New Version</h1>
    </header>

    <div class="container mx-auto p-8 mt-8 bg-gray-850 rounded-lg shadow-xl">
        <form method="POST" class="space-y-4">
            <label for="package_id" class="text-lg font-semibold text-teal-300">Select Package</label>
            <select name="package_id" id="package_id" class="w-full p-3 rounded-lg bg-gray-700 text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                <?php foreach ($packages as $package): ?>
                    <option value="<?php echo htmlspecialchars($package['id_package'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($package['nom_package'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="version" class="text-lg font-semibold text-teal-300">Version</label>
            <input type="text" name="version" id="version" placeholder="Enter version" 
                class="w-full p-3 rounded-lg bg-gray-700 text-gray-300 focus:outline-none focus:ring-2 focus:ring-teal-500">

            <button type="submit" class="bg-teal-600 text-white py-3 px-6 rounded-lg hover:bg-teal-700 transition">
                Add Version
            </button>
        </form>
    </div>

</body>

</html>
