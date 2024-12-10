<?php
require './db.php';

// Search functionality
$query = $_GET['query'] ?? '';

// Fetch packages with their version and authors
$stmt = $pdo->prepare("
    SELECT p.id_package, p.nom_package, p.description, 
       MAX(v.version) AS version, GROUP_CONCAT(a.nom_auteur) AS authors
    FROM Packages p
    LEFT JOIN Versions v ON p.id_package = v.id_package
    LEFT JOIN auteurs_packages ap ON p.id_package = ap.id_package
    LEFT JOIN Auteurs a ON ap.id_auteur = a.id_auteur
    WHERE p.nom_package LIKE :query OR p.description LIKE :query
    GROUP BY p.id_package, p.nom_package, p.description
    ORDER BY p.nom_package
");
$stmt->execute(['query' => "%$query%"]);
$packages = $stmt->fetchAll();
?>
