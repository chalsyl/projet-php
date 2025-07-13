<?php
session_start();
if(!isset($_SESSION['login'])){
    header("location:index.php");
    exit();
}

require_once __DIR__ . '/../src/functions.php';

$stats = getStatistiquesInscriptions($pdo);

$conn = new mysqli('localhost', 'root', '', 'catalogue_formations');
if ($conn->connect_error) {
    die('Erreur de connexion à la base de données : ' . $conn->connect_error);
}

// Statistiques supplémentaires
$total_formations = $conn->query("SELECT COUNT(*) as total FROM formation")->fetch_assoc()['total'];
$total_animateurs = $conn->query("SELECT COUNT(*) as total FROM animateur")->fetch_assoc()['total'];

// Formations les plus populaires
$formations_populaires = $conn->query("
    SELECT f.intitule, f.date_debut, COUNT(i.id_inscription) as nb_inscriptions 
    FROM formation f 
    LEFT JOIN inscription i ON f.id_formation = i.id_formation 
    GROUP BY f.id_formation 
    ORDER BY nb_inscriptions DESC 
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

// Inscriptions récentes
$inscriptions_recentes = $conn->query("
    SELECT p.nom, p.prenom, f.intitule, i.date_inscription 
    FROM inscription i 
    JOIN participant p ON i.id_participant = p.id_participant 
    JOIN formation f ON i.id_formation = f.id_formation 
    ORDER BY i.date_inscription DESC 
    LIMIT 10
")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include "menu.php"; ?>

<h1>Tableau de bord - Statistiques</h1>

<div class="stats-dashboard">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stat-content">
                <h3><?= $total_formations ?></h3>
                <p>Formations disponibles</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3><?= $stats['total_participants'] ?></h3>
                <p>Participants uniques</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-content">
                <h3><?= $stats['total_inscriptions'] ?></h3>
                <p>Total des inscriptions</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stat-content">
                <h3><?= $total_animateurs ?></h3>
                <p>Animateurs</p>
            </div>
        </div>
    </div>

    <div class="dashboard-sections">
        <div class="dashboard-section">
            <h2>
                <i class="fas fa-trophy"></i>
                Formations les plus populaires
            </h2>
            <div class="table-wrapper">
                <table>
                    <tr>
                        <th>Formation</th>
                        <th>Date de début</th>
                        <th>Inscriptions</th>
                    </tr>
                    <?php foreach ($formations_populaires as $formation): ?>
                    <tr>
                        <td><?= htmlspecialchars($formation['intitule']) ?></td>
                        <td><?= date('d/m/Y', strtotime($formation['date_debut'])) ?></td>
                        <td>
                            <span class="badge-count"><?= $formation['nb_inscriptions'] ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

        <div class="dashboard-section">
            <h2>
                <i class="fas fa-clock"></i>
                Inscriptions récentes
            </h2>
            <div class="table-wrapper">
                <table>
                    <tr>
                        <th>Participant</th>
                        <th>Formation</th>
                        <th>Date d'inscription</th>
                    </tr>
                    <?php foreach ($inscriptions_recentes as $inscription): ?>
                    <tr>
                        <td><?= htmlspecialchars($inscription['prenom'] . ' ' . $inscription['nom']) ?></td>
                        <td><?= htmlspecialchars($inscription['intitule']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($inscription['date_inscription'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>

    <div class="dashboard-actions">
        <a href="inscriptions_par_formation.php" class="btn">
            <i class="fas fa-list"></i>
            Voir toutes les inscriptions
        </a>
        <a href="formations.php" class="btn">
            <i class="fas fa-cog"></i>
            Gérer les formations
        </a>
    </div>
</div>

</body>
</html>