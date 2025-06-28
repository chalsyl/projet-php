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
    $conn->query("DELETE FROM formation WHERE id_formation=$id");
    header('Location: formations.php');
    exit();
}
$result = $conn->query("SELECT * FROM formation ORDER BY date_debut DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des formations</title>
<link rel="stylesheet" href="../stylesheets/style.css">
</head>
<body>
<?php include "menu.php"; ?>
<h1>Gestion des formations</h1>
<a href="ajout_formation.php" class="btn" style="margin-bottom: 1.5em; display:inline-block;">Ajouter une formation</a>
<table>
    <tr>
        <th>Intitulé</th>
        <th>Domaine</th>
        <th>Lieu</th>
        <th>Date</th>
        <th>Durée</th>
        <th>Montant</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['intitule']) ?></td>
        <td><?= htmlspecialchars($row['domaine']) ?></td>
        <td><?= htmlspecialchars($row['lieu']) ?></td>
        <td><?= htmlspecialchars($row['date_debut']) ?></td>
        <td><?= htmlspecialchars($row['duree']) ?> j</td>
        <td><?= htmlspecialchars($row['montant']) ?> €</td>
        <td>
            <a href="modif_formation.php?id=<?= $row['id_formation'] ?>">Modifier</a> |
            <a href="formations.php?delete=<?= $row['id_formation'] ?>" onclick="return confirm('Supprimer cette formation ?')" style="color:#d32f2f;text-decoration:none;">Supprimer</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
