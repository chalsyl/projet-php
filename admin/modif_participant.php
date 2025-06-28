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
    die('Participant non trouvé.');
}
$stmt = $conn->prepare("SELECT * FROM participant WHERE id_participant = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$participant = $res->fetch_assoc();
if (!$participant) die('Participant non trouvé.');
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $genre = trim($_POST['genre']);
    if ($nom && $prenom && $email) {
        $stmt = $conn->prepare("UPDATE participant SET nom=?, prenom=?, email=?, telephone=?, genre=? WHERE id_participant=?");
        $stmt->bind_param("sssssi", $nom, $prenom, $email, $telephone, $genre, $id);
        $stmt->execute();
        $message = "<span style='color:green'>Participant modifié !</span>";
    } else {
        $message = "<span style='color:red'>Veuillez remplir tous les champs obligatoires.</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier participant</title>
    <link rel="stylesheet" href="../stylesheets/style.css">
</head>
<body>
<?php include "menu.php"; ?>
<h1>Modifier un participant</h1>
<?= $message ?>
<?php if (strpos(strip_tags(is_string($message) ? $message : ''), 'modifié') !== false): ?>
<div style="display:flex;justify-content:flex-start;margin-bottom:2em;width:100%;">
    <a href="liste_participants.php" class="btn btn-retour">&larr; Retour à la liste</a>
</div>
<?php endif; ?>
<form method="post">
    <div class="field">
        <label>Nom *</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($participant['nom']) ?>" required>
    </div>
    <div class="field">
        <label>Prénom *</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($participant['prenom']) ?>" required>
    </div>
    <div class="field">
        <label>Email *</label>
        <input type="email" name="email" value="<?= htmlspecialchars($participant['email']) ?>" required>
    </div>
    <div class="field">
        <label>Téléphone</label>
        <input type="text" name="telephone" value="<?= htmlspecialchars($participant['telephone']) ?>">
    </div>
    <div class="field">
        <label>Genre</label>
        <input type="text" name="genre" value="<?= htmlspecialchars($participant['genre']) ?>">
    </div>
    <button type="submit">Enregistrer</button>
</form>
</body>
</html>
