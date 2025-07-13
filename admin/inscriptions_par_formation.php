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

// Récupération de l'ID de formation si spécifié
$id_formation = isset($_GET['formation']) ? intval($_GET['formation']) : 0;

// Récupération de toutes les formations pour le sélecteur
$formations_result = $conn->query("SELECT id_formation, intitule, date_debut FROM formation ORDER BY date_debut DESC");

// Récupération des inscriptions selon le filtre
if ($id_formation > 0) {
    $sql = "SELECT p.*, f.intitule as formation_intitule, f.date_debut, i.date_inscription 
            FROM participant p 
            JOIN inscription i ON p.id_participant = i.id_participant 
            JOIN formation f ON i.id_formation = f.id_formation 
            WHERE f.id_formation = ? 
            ORDER BY i.date_inscription DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_formation);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Récupération des infos de la formation sélectionnée
    $formation_info = $conn->query("SELECT intitule, date_debut FROM formation WHERE id_formation = $id_formation")->fetch_assoc();
} else {
    $sql = "SELECT p.*, f.intitule as formation_intitule, f.date_debut, i.date_inscription 
            FROM participant p 
            JOIN inscription i ON p.id_participant = i.id_participant 
            JOIN formation f ON i.id_formation = f.id_formation 
            ORDER BY i.date_inscription DESC";
    $result = $conn->query($sql);
    $formation_info = null;
}

// Suppression d'une inscription
if (isset($_GET['delete_inscription'])) {
    $id_inscription = intval($_GET['delete_inscription']);
    $conn->query("DELETE FROM inscription WHERE id_inscription = $id_inscription");
    header('Location: ' . $_SERVER['PHP_SELF'] . ($id_formation > 0 ? '?formation=' . $id_formation : ''));
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscriptions par Formation</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include "menu.php"; ?>

<div class="admin-header">
    <h1>Gestion des Inscriptions</h1>
    
    <div class="filter-section">
        <form method="GET" class="filter-form">
            <div class="filter-field">
                <label for="formation">Filtrer par formation :</label>
                <select name="formation" id="formation" onchange="this.form.submit()">
                    <option value="">Toutes les formations</option>
                    <?php while($formation = $formations_result->fetch_assoc()): ?>
                        <option value="<?= $formation['id_formation'] ?>" 
                                <?= $id_formation == $formation['id_formation'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($formation['intitule']) ?> 
                            (<?= date('d/m/Y', strtotime($formation['date_debut'])) ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </form>
    </div>

    <?php if ($formation_info): ?>
        <div class="formation-info">
            <h3>Formation sélectionnée :</h3>
            <p><strong><?= htmlspecialchars($formation_info['intitule']) ?></strong></p>
            <p>Date de début : <?= date('d/m/Y', strtotime($formation_info['date_debut'])) ?></p>
        </div>
    <?php endif; ?>
</div>

<div class="stats-summary">
    <div class="stat-item">
        <i class="fas fa-users"></i>
        <span class="stat-number"><?= $result->num_rows ?></span>
        <span class="stat-label">Inscription<?= $result->num_rows > 1 ? 's' : '' ?></span>
    </div>
</div>

<div class="table-wrapper">
<table>
    <tr>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Téléphone</th>
        <th>Genre</th>
        <?php if (!$id_formation): ?>
            <th>Formation</th>
        <?php endif; ?>
        <th>Date inscription</th>
        <th>Actions</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['nom']) ?></td>
            <td><?= htmlspecialchars($row['prenom']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['telephone'] ?: 'Non renseigné') ?></td>
            <td><?= htmlspecialchars($row['genre'] ?: 'Non renseigné') ?></td>
            <?php if (!$id_formation): ?>
                <td><?= htmlspecialchars($row['formation_intitule']) ?></td>
            <?php endif; ?>
            <td><?= date('d/m/Y H:i', strtotime($row['date_inscription'])) ?></td>
            <td>
                <a href="modif_participant.php?id=<?= $row['id_participant'] ?>">Modifier</a> |
                <a href="?<?= $id_formation ? 'formation=' . $id_formation . '&' : '' ?>delete_inscription=<?= $row['id_participant'] ?>" 
                   onclick="return confirm('Supprimer cette inscription ?')" 
                   style="color:#d32f2f;text-decoration:none;">Désinscrire</a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="<?= $id_formation ? '7' : '8' ?>" style="text-align:center;padding:2rem;color:var(--admin-text-muted);">
                Aucune inscription trouvée<?= $id_formation ? ' pour cette formation' : '' ?>.
            </td>
        </tr>
    <?php endif; ?>
</table>
</div>

<div class="admin-actions">
    <a href="liste_participants.php" class="btn btn-retour">
        <i class="fas fa-arrow-left"></i> Retour à la liste complète
    </a>
    <?php if ($id_formation): ?>
        <a href="inscriptions_par_formation.php" class="btn">
            <i class="fas fa-list"></i> Voir toutes les inscriptions
        </a>
    <?php endif; ?>
</div>

</body>
</html>