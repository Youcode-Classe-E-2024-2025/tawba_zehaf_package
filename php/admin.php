<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
require 'php\db.php';

// Handle package addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_package'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO Packages (nom_package, description, date_ajout) VALUES (:name, :description, NOW())");
    $stmt->execute(['name' => $name, 'description' => $description]);
    header('Location: admin.php'); // Refresh to display the new package
}

// Handle package deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM Packages WHERE id_package = :id");
    $stmt->execute(['id' => $id]);
    header('Location: admin.php'); // Refresh the page
}

// Handle version addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_version'])) {
    $package_id = $_POST['package_id'];
    $version = $_POST['version'];
    $stmt = $pdo->prepare("INSERT INTO Versions (id_package, version, date_release) VALUES (:package_id, :version, NOW())");
    $stmt->execute(['package_id' => $package_id, 'version' => $version]);
    header('Location: admin.php'); // Refresh to display the new version
}

// Handle authors addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_author'])) {
    $package_id = $_POST['package_id'];
    $author_id = $_POST['author_id'];
    $stmt = $pdo->prepare("INSERT INTO auteurs_packages (id_package, id_auteur) VALUES (:package_id, :author_id)");
    $stmt->execute(['package_id' => $package_id, 'author_id' => $author_id]);
    header('Location: admin.php'); // Refresh to display the new author
}

// Handle author addition (for the authors table itself)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_new_author'])) {
    $author_name = $_POST['author_name'];
    $email = $_POST['email'];  // Get the email input

    $stmt = $pdo->prepare("INSERT INTO Auteurs (nom_auteur, email) VALUES (:author_name, :email)");
    $stmt->execute(['author_name' => $author_name, 'email' => $email]);
    header('Location: admin.php'); // Refresh to display the new author
}
?>
 <!DOCTYPE html>  
 <html>  
 <head>  
      <meta charset="utf-8">  
      <meta name="viewport" content="width=device-width, initial-scale=1">  
      <link rel="stylesheet" type="text/css" href="css/style.css">  
      <title>Login Page</title>  
 </head>  
 <body>  
 <div class="main">  
      <div class="flex">  
           <div class="content">  
                <h2 class="title">Login</h2>  
                <form method="post" action="">  
                     <label for="username">Username</label>  
                     <div class="box">  
                          <input type="text" name="email" placeholder="Username" class="form-control" required>  
                     </div>  
                     <label for="password">Password</label>  
                     <div class="box">  
                          <input type="password" name="password" placeholder="Password" class="form-control" required>  
                     </div>  
                     <div class="btn-box">  
                          <input type="submit" name="submit" value="Login" class="btn submit-btn">  
                     </div>  
                     <div class="error">  
                          <?php echo $msg ?>  
                     </div>  
                </form>  
           </div>  
      </div>  
 </div>  
 </body>  
 </html>  








?>