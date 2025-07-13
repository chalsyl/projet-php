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
if ((isset($_GET['delete']) && ($id = intval($_GET['delete']))) || ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && isset($_POST['id']))) {
    if (!isset($id)) $id = intval($_POST['id']);
    $conn->query("DELETE FROM participant WHERE id_participant=$id");
    header('Location: liste_participants.php');
    exit();
}
$sql = "SELECT * FROM participant ORDER BY nom, prenom";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des participants</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include "menu.php"; ?>
<h1>Liste des participants</h1>
<table>
    <tr>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Genre</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['nom']) ?></td>
        <td><?= htmlspecialchars($row['prenom']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['genre']) ?></td>
        <td>
            <a href="modif_participant.php?id=<?= $row['id_participant'] ?>">Modifier</a> |
            <a href="?delete=<?= $row['id_participant'] ?>" onclick="return confirm('Supprimer ce participant ?')" style="color:#d32f2f;text-decoration:none;">Supprimer</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
