<?php
require_once __DIR__ . '/../src/functions.php';

// Récupération des paramètres de recherche
$search_term = trim($_GET['search'] ?? '');
$domaine_filter = trim($_GET['domaine'] ?? '');
$lieu_filter = trim($_GET['lieu'] ?? '');
$date_debut = $_GET['date_debut'] ?? '';
$date_fin = $_GET['date_fin'] ?? '';

// Récupération des formations avec filtres
$formations = rechercherFormations($pdo, $search_term, $domaine_filter, $lieu_filter, $date_debut, $date_fin);

// Récupération des domaines et lieux pour les filtres
$domaines = getDomainesDistincts($pdo);
$lieux = getLieuxDistincts($pdo);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de Formations</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
                <a href="index.php"  class="active">Formations</a>
                <a href="inscription.php">Inscription</a>
                <a href="../admin/index.php">Admin</a>
            </h3>
            </nav>
        </div>
    </header>

    <main>
        <section class="search-section">
            <div class="search-container">
                <h2 class="search-title">Rechercher une formation</h2>
                
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

        <section class="results-section">
            <div class="results-header">
                <h3 class="results-title">
                    <i class="fas fa-list"></i>
                    Résultats de la recherche
                    <span class="results-count">(<?= count($formations) ?> formation<?= count($formations) > 1 ? 's' : '' ?> trouvée<?= count($formations) > 1 ? 's' : '' ?>)</span>
                </h3>
            </div>

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
                                    <span>Consulter le programme</span>
                                </button>
                                <button onclick="window.location.href='inscription.php?formation=<?= $formation['id_formation'] ?>'" class="card-btn">
                                    <i class="fas fa-user-plus"></i>
                                    <span>S'inscrire</span>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <h3>Aucune formation trouvée</h3>
                        <p>Essayez de modifier vos critères de recherche ou consultez toutes nos formations.</p>
                        <a href="formations.php" class="cta primary-cta">Voir toutes les formations</a>
                    </div>
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
                    <a href="#"><i class="fab fa-x-twitter"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 - Sylla Industries.</p>
        </div>
    </footer>

    <script src="app.js"></script>
</body>
</html>