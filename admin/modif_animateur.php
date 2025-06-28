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
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die('Animateur non trouvé.');
}
$stmt = $conn->prepare("SELECT * FROM animateur WHERE id_animateur = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$animateur = $res->fetch_assoc();
if (!$animateur) die('Animateur non trouvé.');
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    if ($nom && $prenom) {
        $stmt = $conn->prepare("UPDATE animateur SET nom=?, prenom=? WHERE id_animateur=?");
        $stmt->bind_param("ssi", $nom, $prenom, $id);
        $stmt->execute();
        $message = "<span style='color:green'>Animateur modifié !</span>";
    } else {
        $message = "<span style='color:red'>Veuillez saisir le nom et le prénom.</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier animateur</title>
<link rel="stylesheet" href="../stylesheets/style.css"></head>
<body>
<?php include "menu.php"; ?>
<h1>Modifier un animateur</h1>
<?= $message ?>
<?php if (strpos(strip_tags(is_string($message) ? $message : ''), 'modifié') !== false): ?>
<div style="display:flex;justify-content:flex-start;margin-bottom:2em;width:100%;">
    <a href="animateurs.php" class="btn btn-retour">&larr; Retour à la liste</a>
</div>
<?php endif; ?>
<form method="post">
    <div class="field">
        <label>Nom *</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($animateur['nom']) ?>" required>
    </div>
    <div class="field">
        <label>Prénom *</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($animateur['prenom']) ?>" required>
    </div>
    <button type="submit">Enregistrer</button>
</form>
</body>
</html>
