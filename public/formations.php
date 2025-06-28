<?php
require_once __DIR__ . '/../src/functions.php';
$formations = getFormations($pdo);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formations</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="transparent-header">
        <div class="header-content">
            <nav class="header-nav-logo">
                <a href="index.php" class="header-logo">
                    <img src="./logo.png" alt="Logo" />
                </a>
            </nav>
            <nav class="header-nav-links">
                <h3>
                <a href="index.php">Accueil</a>
                <a href="formations.php">Formations</a>
                <a href="inscription.php">Inscription</a>
                <a href="../admin/index.php">Admin</a>
            </h3>
            </nav>
        </div>
    </header>
    <main>
        <section id="formations">
            <h2 style="text-align:center;margin-bottom:2rem;">Nos Formations</h2>
            <div class="catalogue">
                <?php if (!empty($formations)): ?>
                    <?php foreach ($formations as $formation): ?>
                        <div class="card">
                            <div class="card-title"><?= htmlspecialchars($formation['intitule']) ?></div>
                            <div class="card-domain"><?= htmlspecialchars($formation['domaine']) ?></div>
                            <div class="card-info">
                                Lieu : <?= htmlspecialchars($formation['lieu']) ?> &nbsp; | &nbsp;
                                Début : <?= date('d/m/Y', strtotime($formation['date_debut'])) ?> &nbsp; | &nbsp;
                                Durée : <?= (int)$formation['duree'] ?> jours
                            </div>
                            <div class="card-info">Montant : <?= number_format($formation['montant'], 2, ',', ' ') ?>€</div>
                            <div class="card-anim">
                                Animateurs :
                                <?php
                                $animateurs = getAnimateursByFormation($pdo, $formation['id_formation']);
                                $noms = array_map(function($a) {
                                    return $a['prenom'] . ' ' . $a['nom'];
                                }, $animateurs);
                                echo htmlspecialchars(implode(', ', $noms));
                                ?>
                            </div>
                            <div class="card-actions">
                                <button onclick="window.location.href='programme.php?id=<?= $formation['id_formation'] ?>'" class="card-btn">
                                    <i class="fas fa-book"></i>
                                    Consulter le programme
                                </button>
                                <button onclick="window.location.href='inscription.php?formation=<?= $formation['id_formation'] ?>'" class="card-btn">
                                    <i class="fas fa-user-plus"></i>
                                    S'inscrire
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align:center;grid-column:1/-1;">Aucune formation disponible pour le moment.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <footer class="modern-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>À propos</h3>
                <p>Leader de la formation continue professionnelle depuis 2020</p>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: contact@formations.com</p>
                <p>Tél: 69 69 69 69 69</p>
            </div>
            <div class="footer-section">
                <h3>Suivez-nous</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 - Plateforme de Formations Continues</p>
        </div>
    </footer>
    <script src="app.js"></script>
</body>
</html>
