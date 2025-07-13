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
// Récupérer la liste des animateurs
$animateurs = $conn->query("SELECT * FROM animateur ORDER BY nom, prenom");
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
        $stmt = $conn->prepare("INSERT INTO formation (domaine, intitule, lieu, date_debut, duree, montant, fiche_programme) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssids", $domaine, $intitule, $lieu, $date_debut, $duree, $montant, $fiche_programme);
        $stmt->execute();
        $formation_id = $stmt->insert_id;
        // Lier les animateurs
        foreach ($anim_ids as $aid) {
            $conn->query("INSERT INTO formation_animateur (id_formation, id_animateur) VALUES ($formation_id, " . intval($aid) . ")");
        }
        $message = "<span style='color:green'>Formation ajoutée !</span>";
    } else {
        $message = "<span style='color:red'>Veuillez remplir tous les champs obligatoires.</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajout formation</title>
<link rel="stylesheet" href="admin.css"></head>
<body>
<?php include "menu.php"; ?>
<h1>Ajouter une formation</h1>
<?= $message ?>
<form method="post">
    <div class="field">
        <label>Domaine *</label>
        <input type="text" name="domaine" required>
    </div>
    <div class="field">
        <label>Intitulé *</label>
        <input type="text" name="intitule" required>
    </div>
    <div class="field">
        <label>Lieu *</label>
        <input type="text" name="lieu" required>
    </div>
    <div class="field">
        <label>Date de début *</label>
        <input type="date" name="date_debut" required>
    </div>
    <div class="field">
        <label>Durée (jours) *</label>
        <input type="number" name="duree" min="1" required>
    </div>
    <div class="field">
        <label>Montant (€) *</label>
        <input type="number" name="montant" min="0" step="0.01" required>
    </div>
    <div class="field">
        <label>Animateur(s) *</label>
        <select name="animateurs[]" multiple required>
            <?php while($a = $animateurs->fetch_assoc()): ?>
                <option value="<?= $a['id_animateur'] ?>"><?= htmlspecialchars($a['nom']) ?> <?= htmlspecialchars($a['prenom']) ?></option>
            <?php endwhile; ?>
        </select>
        <small style="color: var(--admin-text-muted); font-size: 0.85rem; margin-top: 0.5rem; display: block;">
            Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs animateurs
        </small>
    </div>
    <div class="field">
        <label>Fiche programme</label>
        <textarea name="fiche_programme" rows="5"></textarea>
    </div>
    <button type="submit">Ajouter</button>
</form>
</body>
</html>