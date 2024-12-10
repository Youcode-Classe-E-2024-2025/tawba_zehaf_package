<?php
$host = 'localhost';
$db = 'package_management';
$user = 'root'; 
$password = ''; 
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
