<?php
require_once __DIR__ . '/../src/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: formations.php');
    exit;
}

$formation = getFicheProgramme($pdo, (int)$_GET['id']);
if (!$formation) {
    header('Location: formations.php');
    exit;
}

$animateurs = explode('||', $formation['animateurs']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programme - <?= htmlspecialchars($formation['intitule']) ?></title>
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

    <div class="programme-container">
        <div class="programme-header">
            <h1 class="programme-title"><?= htmlspecialchars($formation['intitule']) ?></h1>
            <div class="programme-meta">
                <div class="meta-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><?= htmlspecialchars($formation['lieu']) ?></span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span><?= date('d/m/Y', strtotime($formation['date_debut'])) ?></span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <span><?= (int)$formation['duree'] ?> jours</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-euro-sign"></i>
                    <span><?= number_format($formation['montant'], 2, ',', ' ') ?>€</span>
                </div>
            </div>
        </div>

        <div class="programme-section">
            <h2 class="section-title">
                <i class="fas fa-bullseye"></i>
                Objectifs de la formation
            </h2>
            <ul class="objectifs-list">
                <li>Maîtriser les concepts fondamentaux et avancés</li>
                <li>Acquérir une expertise pratique à travers des cas concrets</li>
                <li>Développer des compétences professionnelles applicables immédiatement</li>
                <li>Obtenir une certification reconnue dans le domaine</li>
            </ul>
        </div>

        <div class="programme-section">
            <h2 class="section-title">
                <i class="fas fa-list-check"></i>
                Prérequis
            </h2>
            <ul class="prerequis-list">
                <li>Connaissances de base dans le domaine</li>
                <li>Expérience professionnelle recommandée</li>
                <li>Ordinateur portable pour les exercices pratiques</li>
            </ul>
        </div>

        <div class="programme-section">
            <h2 class="section-title">
                <i class="fas fa-book"></i>
                Programme détaillé
            </h2>
            <div class="programme-contenu">
                <div class="module">
                    <div class="module-title">
                        Module 1 : Introduction et fondamentaux
                        <span class="module-duration">1 jour</span>
                    </div>
                    <ul class="module-items">
                        <li>Présentation des concepts clés</li>
                        <li>Méthodologie et bonnes pratiques</li>
                        <li>Exercices pratiques d'application</li>
                    </ul>
                </div>
                
                <div class="module">
                    <div class="module-title">
                        Module 2 : Approfondissement
                        <span class="module-duration">2 jours</span>
                    </div>
                    <ul class="module-items">
                        <li>Études de cas concrets</li>
                        <li>Travaux pratiques en groupe</li>
                        <li>Mise en situation professionnelle</li>
                    </ul>
                </div>
                
                <div class="module">
                    <div class="module-title">
                        Module 3 : Expertise et certification
                        <span class="module-duration">2 jours</span>
                    </div>
                    <ul class="module-items">
                        <li>Techniques avancées</li>
                        <li>Projet final</li>
                        <li>Préparation à la certification</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="programme-section">
            <h2 class="section-title">
                <i class="fas fa-chalkboard-teacher"></i>
                Formateurs
            </h2>
            <div class="formateur-section">
                <?php foreach ($animateurs as $animateur): ?>
                <div class="formateur-card">
                    <img src="assets/avatar-placeholder.jpg" alt="<?= htmlspecialchars($animateur) ?>" class="formateur-avatar">
                    <h3 class="formateur-name"><?= htmlspecialchars($animateur) ?></h3>
                    <div class="formateur-title">Expert <?= htmlspecialchars($formation['domaine']) ?></div>
                    <p class="formateur-bio">
                        Professionnel expérimenté avec plus de 10 ans d'expertise dans le domaine.
                        Formateur certifié et consultant international.
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="programme-footer">
            <div class="programme-actions">
                <a href="inscription.php?formation=<?= $formation['id_formation'] ?>" class="programme-btn btn-inscription">
                    <i class="fas fa-user-plus"></i> S'inscrire
                </a>
                <a href="formations.php" class="programme-btn btn-retour">
                    <i class="fas fa-arrow-left"></i> Retour au catalogue
                </a>
            </div>
        </div>
    </div>

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
                    <a href="#"><i class="fab fa-facebook"></i></a>
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
