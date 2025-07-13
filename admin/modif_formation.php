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
    die('Formation non trouvée.');
}
// Récupérer la formation
$stmt = $conn->prepare("SELECT * FROM formation WHERE id_formation = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$formation = $res->fetch_assoc();
if (!$formation) die('Formation non trouvée.');
// Récupérer les animateurs
$animateurs = $conn->query("SELECT * FROM animateur ORDER BY nom, prenom");
// Animateurs déjà liés
$anims_linked = [];
$res2 = $conn->query("SELECT id_animateur FROM formation_animateur WHERE id_formation = $id");
while($row = $res2->fetch_assoc()) $anims_linked[] = $row['id_animateur'];
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $domaine = trim($_POST['domaine']);
    $intitule = trim($_POST['intitule']);
    $lieu = trim($_POST['lieu']);
    $date_debut = $_POST['date_debut'];
    $duree = intval($_POST['duree']);
    $montant = floatval($_POST['montant']);
    $fiche_programme = trim($_POST['fiche_programme']);
    $anim_ids = $_POST['animateurs'] ?? [];
    if ($domaine && $intitule && $lieu && $date_debut && $duree && $montant) {
        $stmt = $conn->prepare("UPDATE formation SET domaine=?, intitule=?, lieu=?, date_debut=?, duree=?, montant=?, fiche_programme=? WHERE id_formation=?");
        $stmt->bind_param("ssssidsi", $domaine, $intitule, $lieu, $date_debut, $duree, $montant, $fiche_programme, $id);
        $stmt->execute();
        // Mettre à jour les animateurs
        $conn->query("DELETE FROM formation_animateur WHERE id_formation=$id");
        foreach ($anim_ids as $aid) {
            $conn->query("INSERT INTO formation_animateur (id_formation, id_animateur) VALUES ($id, " . intval($aid) . ")");
        }
        $message = "<span style='color:green'>Formation modifiée !</span>";
    } else {
        $message = "<span style='color:red'>Veuillez remplir tous les champs obligatoires.</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier formation</title>
<link rel="stylesheet" href="admin.css"></head>
<body>
<?php include "menu.php"; ?>
<h1>Modifier une formation</h1>
<?= $message ?>
<?php if (strpos(strip_tags(is_string($message) ? $message : ''), 'modifiée') !== false): ?>
<div style="display:flex;justify-content:flex-start;margin-bottom:2em;width:100%;">
    <a href="formations.php" class="btn btn-retour">&larr; Retour à la liste</a>
</div>
<?php endif; ?>
<form method="post">
    <div class="field">
        <label>Domaine *</label>
        <input type="text" name="domaine" value="<?= htmlspecialchars($formation['domaine']) ?>" required>
    </div>
    <div class="field">
        <label>Intitulé *</label>
        <input type="text" name="intitule" value="<?= htmlspecialchars($formation['intitule']) ?>" required>
    </div>
    <div class="field">
        <label>Lieu *</label>
        <input type="text" name="lieu" value="<?= htmlspecialchars($formation['lieu']) ?>" required>
    </div>
    <div class="field">
        <label>Date de début *</label>
        <input type="date" name="date_debut" value="<?= htmlspecialchars($formation['date_debut']) ?>" required>
    </div>
    <div class="field">
        <label>Durée (jours) *</label>
        <input type="number" name="duree" min="1" value="<?= htmlspecialchars($formation['duree']) ?>" required>
    </div>
    <div class="field">
        <label>Montant (€) *</label>
        <input type="number" name="montant" min="0" step="0.01" value="<?= htmlspecialchars($formation['montant']) ?>" required>
    </div>
    <div class="field">
        <label>Animateur(s) *</label>
        <select name="animateurs[]" multiple required>
            <?php while($a = $animateurs->fetch_assoc()): ?>
                <option value="<?= $a['id_animateur'] ?>" <?= in_array($a['id_animateur'], $anims_linked) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($a['nom']) ?> <?= htmlspecialchars($a['prenom']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <small style="color: var(--admin-text-muted); font-size: 0.85rem; margin-top: 0.5rem; display: block;">
            Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs animateurs
        </small>
    </div>
    <div class="field">
        <label>Fiche programme</label>
        <textarea name="fiche_programme" rows="5"><?= htmlspecialchars($formation['fiche_programme']) ?></textarea>
    </div>
    <button type="submit">Enregistrer</button>
</form>
</body>
</html>