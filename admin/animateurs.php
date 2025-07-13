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
// Suppression
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM animateur WHERE id_animateur=$id");
    header('Location: animateurs.php');
    exit();
}
$result = $conn->query("SELECT * FROM animateur ORDER BY nom, prenom");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des animateurs</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include "menu.php"; ?>
<h1>Gestion des animateurs</h1>
<a href="ajout_animateur.php" class="btn" style="margin-bottom: 1.5em; display:inline-block;">Ajouter un animateur</a>
<table>
    <tr>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['nom']) ?></td>
        <td><?= htmlspecialchars($row['prenom']) ?></td>
        <td>
            <a href="modif_animateur.php?id=<?= $row['id_animateur'] ?>">Modifier</a> |
            <a href="animateurs.php?delete=<?= $row['id_animateur'] ?>" onclick="return confirm('Supprimer cet animateur ?')" style="color:#d32f2f;text-decoration:none;">Supprimer</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
