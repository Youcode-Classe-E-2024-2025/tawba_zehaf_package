<?php
session_start();
require ' php\db.php'; 
// Initialiser les packages si non définis
if (!isset($_SESSION['packages'])) {
    $_SESSION['packages'] = [];
}

// Variables pour les champs du formulaire
$name = "";
$author = "";
$version = "";
$isEditing = false;
$editingIndex = -1;

// Ajouter ou modifier un package
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_or_update'])) {
        $name = $_POST['package_name'];
        $author = $_POST['package_author'];
        $version = $_POST['package_version'];

        if ($_POST['editing_index'] !== "") {
            // Modifier un package existant
            $index = (int)$_POST['editing_index'];
            $_SESSION['packages'][$index] = ['name' => $name, 'author' => $author, 'version' => $version];
        } else {
            // Ajouter un nouveau package
            $_SESSION['packages'][] = ['name' => $name, 'author' => $author, 'version' => $version];
        }
        header("Location: packages.php");
        exit;
    }

    // Supprimer un package
    if (isset($_POST['delete'])) {
        $index = (int)$_POST['index'];
        array_splice($_SESSION['packages'], $index, 1);
        header("Location: packages.php");
        exit;
    }

    // Charger un package pour modification
    if (isset($_POST['edit'])) {
        $editingIndex = (int)$_POST['index'];
        $package = $_SESSION['packages'][$editingIndex];
        $name = $package['name'];
        $author = $package['author'];
        $version = $package['version'];
        $isEditing = true;
    }
}
?>