<?php
require_once __DIR__ . '/../src/functions.php';

// Récupération des paramètres de recherche
$search_term = trim($_GET['search'] ?? '');
$domaine_filter = trim($_GET['domaine'] ?? '');
$lieu_filter = trim($_GET['lieu'] ?? '');
$date_debut = $_GET['date_debut'] ?? '';
$date_fin = $_GET['date_fin'] ?? '';

// Récupération des formations avec filtres
if ($search_term || $domaine_filter || $lieu_filter || $date_debut || $date_fin) {
    $formations = rechercherFormations($pdo, $search_term, $domaine_filter, $lieu_filter, $date_debut, $date_fin);
} else {
    $formations = getFormations($pdo);
}

// Récupération des domaines et lieux pour les filtres
$domaines = getDomainesDistincts($pdo);
$lieux = getLieuxDistincts($pdo);
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
        <!-- Header des formations -->
        <section class="formations-header">
            <div class="formations-header-content">
                <h2>Catalogue de Formations</h2>
                <p>Découvrez notre catalogue complet de formations professionnelles adaptées à vos besoins</p>
            </div>
        </section>

        <!-- Section de recherche intégrée -->
        <section class="search-section">
            <div class="search-container">
                <h2 class="search-title">
                    <i class="fas fa-search"></i>
                    Rechercher une formation
                </h2>
                
                <form method="GET" class="search-form">
                    <div class="search-grid">
                        <div class="search-field">
                            <label for="search">
                                <i class="fas fa-search"></i>
                                Recherche générale
                            </label>
                            <input type="text" id="search" name="search" 
                                   placeholder="Intitulé, domaine, lieu..." 
                                   value="<?= htmlspecialchars($search_term) ?>">
                        </div>

                        <div class="search-field">
                            <label for="domaine">
                                <i class="fas fa-tag"></i>
                                Domaine
                            </label>
                            <select id="domaine" name="domaine">
                                <option value="">Tous les domaines</option>
                                <?php foreach ($domaines as $domaine): ?>
                                    <option value="<?= htmlspecialchars($domaine['domaine']) ?>" 
                                            <?= $domaine_filter === $domaine['domaine'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($domaine['domaine']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="search-field">
                            <label for="lieu">
                                <i class="fas fa-map-marker-alt"></i>
                                Lieu
                            </label>
                            <select id="lieu" name="lieu">
                                <option value="">Tous les lieux</option>
                                <?php foreach ($lieux as $lieu): ?>
                                    <option value="<?= htmlspecialchars($lieu['lieu']) ?>" 
                                            <?= $lieu_filter === $lieu['lieu'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($lieu['lieu']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="search-field">
                            <label for="date_debut">
                                <i class="fas fa-calendar-alt"></i>
                                Date de début (à partir de)
                            </label>
                            <input type="date" id="date_debut" name="date_debut" 
                                   value="<?= htmlspecialchars($date_debut) ?>">
                        </div>

                        <div class="search-field">
                            <label for="date_fin">
                                <i class="fas fa-calendar-check"></i>
                                Date de fin (jusqu'à)
                            </label>
                            <input type="date" id="date_fin" name="date_fin" 
                                   value="<?= htmlspecialchars($date_fin) ?>">
                        </div>

                        <div class="search-actions">
                            <button type="submit" class="search-btn primary">
                                <i class="fas fa-search"></i>
                                Rechercher
                            </button>
                            <a href="formations.php" class="search-btn secondary">
                                <i class="fas fa-times"></i>
                                Effacer
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <!-- Section des résultats -->
        <section class="results-section">
            <div class="results-header">
                <h3 class="results-title">
                    <i class="fas fa-graduation-cap"></i>
                    <?php if ($search_term || $domaine_filter || $lieu_filter || $date_debut || $date_fin): ?>
                        Résultats de la recherche
                    <?php else: ?>
                        Toutes nos formations
                    <?php endif; ?>
                    <span class="results-count">(<?= count($formations) ?> formation<?= count($formations) > 1 ? 's' : '' ?> trouvée<?= count($formations) > 1 ? 's' : '' ?>)</span>
                </h3>
            </div>

            <div class="catalogue">
                <?php if (!empty($formations)): ?>
                    <?php foreach ($formations as $formation): ?>
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title"><?= htmlspecialchars($formation['intitule']) ?></div>
                                <div class="card-domain">
                                    <i class="fas fa-tag"></i>
                                    <?= htmlspecialchars($formation['domaine']) ?>
                                </div>
                            </div>
                            
                            <div class="card-content">
                                <div class="card-info-grid">
                                    <div class="card-info-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span class="info-label">Lieu :</span>
                                        <span class="info-value"><?= htmlspecialchars($formation['lieu']) ?></span>
                                    </div>
                                    
                                    <div class="card-info-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span class="info-label">Début :</span>
                                        <span class="info-value"><?= date('d/m/Y', strtotime($formation['date_debut'])) ?></span>
                                    </div>
                                    
                                    <div class="card-info-item">
                                        <i class="fas fa-clock"></i>
                                        <span class="info-label">Durée :</span>
                                        <span class="info-value"><?= (int)$formation['duree'] ?> jour<?= (int)$formation['duree'] > 1 ? 's' : '' ?></span>
                                    </div>
                                    
                                    <div class="card-info-item">
                                        <i class="fas fa-euro-sign"></i>
                                        <span class="info-label">Montant :</span>
                                        <span class="info-value price"><?= number_format($formation['montant'], 2, ',', ' ') ?>€</span>
                                    </div>
                                </div>

                                <div class="card-animateurs">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <span class="animateurs-label">Formateur<?php 
                                        $animateurs = getAnimateursByFormation($pdo, $formation['id_formation']);
                                        echo count($animateurs) > 1 ? 's' : '';
                                    ?> :</span>
                                    <div class="animateurs-list">
                                        <?php
                                        $noms = array_map(function($a) {
                                            return $a['prenom'] . ' ' . $a['nom'];
                                        }, $animateurs);
                                        echo htmlspecialchars(implode(', ', $noms));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-actions">
                                <button onclick="window.location.href='programme.php?id=<?= $formation['id_formation'] ?>'" class="card-btn primary">
                                    <i class="fas fa-book-open"></i>
                                    <span>Programme détaillé</span>
                                </button>
                                <button onclick="window.location.href='inscription.php?formation=<?= $formation['id_formation'] ?>'" class="card-btn secondary">
                                    <i class="fas fa-user-plus"></i>
                                    <span>S'inscrire maintenant</span>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-results">
                        <div class="no-results-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>Aucune formation trouvée</h3>
                        <p>Aucune formation ne correspond à vos critères de recherche.</p>
                        <div class="no-results-suggestions">
                            <p><strong>Suggestions :</strong></p>
                            <ul>
                                <li>Vérifiez l'orthographe de vos mots-clés</li>
                                <li>Essayez des termes plus généraux</li>
                                <li>Modifiez vos filtres de recherche</li>
                                <li>Consultez toutes nos formations disponibles</li>
                            </ul>
                        </div>
                        <div class="no-results-actions">
                            <a href="formations.php" class="cta primary-cta">
                                <i class="fas fa-list"></i>
                                Voir toutes les formations
                            </a>
                            <a href="index.php" class="cta secondary-cta">
                                <i class="fas fa-home"></i>
                                Retour à l'accueil
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Section statistiques rapides -->
        <?php if (!empty($formations)): ?>
        <section class="quick-stats">
            <div class="quick-stats-container">
                <div class="quick-stat">
                    <i class="fas fa-graduation-cap"></i>
                    <span class="stat-number"><?= count($formations) ?></span>
                    <span class="stat-label">Formation<?= count($formations) > 1 ? 's' : '' ?> disponible<?= count($formations) > 1 ? 's' : '' ?></span>
                </div>
                <div class="quick-stat">
                    <i class="fas fa-map-marker-alt"></i>
                    <span class="stat-number"><?= count($lieux) ?></span>
                    <span class="stat-label">Lieu<?= count($lieux) > 1 ? 'x' : '' ?> de formation</span>
                </div>
                <div class="quick-stat">
                    <i class="fas fa-tag"></i>
                    <span class="stat-number"><?= count($domaines) ?></span>
                    <span class="stat-label">Domaine<?= count($domaines) > 1 ? 's' : '' ?> d'expertise</span>
                </div>
            </div>
        </section>
        <?php endif; ?>
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
                    <a href="#"><i class="fab fa-x-twitter"></i></a>
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