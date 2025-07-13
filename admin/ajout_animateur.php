<?php
session_start();
if(!isset($_SESSION['login'])){
    header("location:index.php");
    exit();
}
$conn = new mysqli('localhost', 'root', '', 'catalogue_formations');
if ($conn->connect_error) {
    die('Erreur de connexion à la base de données : ' . $conn->connect_error);
}
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    if ($nom && $prenom) {
        $stmt = $conn->prepare("INSERT INTO animateur (nom, prenom) VALUES (?, ?)");
        $stmt->bind_param("ss", $nom, $prenom);
        $stmt->execute();
        $message = "<span style='color:green'>Animateur ajouté !</span>";
    } else {
        $message = "<span style='color:red'>Veuillez saisir le nom et le prénom.</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajout animateur</title>
<link rel="stylesheet" href="admin.css"></head>
<body>
<?php include "menu.php"; ?>
<h1>Ajouter un animateur</h1>
<?= $message ?>
<form method="post">
    <div class="field">
        <label>Nom *</label>
        <input type="text" name="nom" required>
    </div>
    <div class="field">
        <label>Prénom *</label>
        <input type="text" name="prenom" required>
    </div>
    <button type="submit">Ajouter</button>
</form>
</body>
</html>
