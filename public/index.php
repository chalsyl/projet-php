<?php
require_once __DIR__ . '/../src/functions.php';
$stats = [
    'formations' => count(getFormations($pdo)),
    'participants' => 1500, // Exemple
    'satisfaction' => 98    // Exemple
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Catalogue de Formations</title>
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
                <a href="index.php"  class="active">Accueil</a>
                <a href="formations.php">Formations</a>
                <a href="inscription.php">Inscription</a>
                <a href="../admin/index.php">Admin</a>
            </h3>
            </nav>
        </div>
    </header>

    <main>
        <!-- Hero Section avec parallax -->
        <section id="hero" class="hero-section">
            <div class="hero-content">
                <h2 class="hero-title">Formez-vous pour l'avenir</h2>
                <p class="hero-subtitle">Des formations professionnelles adaptées à vos besoins</p>
                <div class="hero-cta">
                    <a href="formations.php" class="cta primary-cta">Découvrir nos formations</a>
                    <a href="inscription.php" class="cta secondary-cta">S'inscrire à une formation</a>
                </div>
            </div>
        </section>

        <!-- Statistiques -->
        <section class="stats-section">
            <div class="stats-container">
                <div class="stat-card">
                    <i class="fas fa-graduation-cap"></i>
                    <h3><?= $stats['formations'] ?></h3>
                    <p>Formations disponibles</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <h3><?= $stats['participants'] ?>+</h3>
                    <p>Participants formés</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-star"></i>
                    <h3><?= $stats['satisfaction'] ?>%</h3>
                    <p>Taux de satisfaction</p>
                </div>
            </div>
        </section>

        <!-- Domaines de formation -->
        <section class="domains-section">
            <h2>Nos domaines d'expertise</h2>
            <div class="domains-grid">
                <div class="domain-card">
                    <i class="fas fa-laptop-code"></i>
                    <h3>Informatique</h3>
                    <p>Développement, cybersécurité, cloud computing</p>
                </div>
                <div class="domain-card">
                    <i class="fas fa-chart-line"></i>
                    <h3>Management</h3>
                    <p>Leadership, gestion de projet, communication</p>
                </div>
                <div class="domain-card">
                    <i class="fas fa-cogs"></i>
                    <h3>Innovation</h3>
                    <p>IA, blockchain, transformation digitale</p>
                </div>
                <div class="domain-card">
                    <i class="fas fa-bullhorn"></i>
                    <h3>Marketing</h3>
                    <p>Digital marketing, social media, analytics</p>
                </div>
            </div>
        </section>

        <!-- Témoignages -->
        <section class="testimonials-section">
            <div class="testimonial-block">
            <h2>Ce qu'ils disent de nous</h2>
            <div class="testimonials-carousel">
                <div class="testimonial-card">
                    <div class="testimonial-icon" style="background: linear-gradient(135deg, #6B46C1, #8B5CF6)">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <div class="testimonial-content">"Excellent séminaire qui m'a permis de renforcer mes compétences en communication."</div>
                    <div class="testimonial-author">MARTIN DUPONT</div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-icon" style="background: linear-gradient(135deg, #A855F7, #C084FC)">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="testimonial-content">"Les formateurs étaient très compétents et engageants."</div>
                    <div class="testimonial-author">SOPHIE MARTIN</div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-icon" style="background: linear-gradient(135deg, #EC4899, #F472B6)">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <div class="testimonial-content">"Formation très enrichissante et directement applicable."</div>
                    <div class="testimonial-author">THOMAS LEROY</div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-icon" style="background: linear-gradient(135deg, #14B8A6, #34D399)">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <div class="testimonial-content">"J'ai apprécié l'ambiance conviviale et l'accompagnement personnalisé."</div>
                    <div class="testimonial-author">AMELIE DURAND</div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-icon" style="background: linear-gradient(135deg, #F59E0B, #FBBF24)">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="testimonial-content">"Des outils concrets et des conseils pratiques pour progresser."</div>
                    <div class="testimonial-author">JULIE BENOIT</div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-icon" style="background: linear-gradient(135deg, #3B82F6, #60A5FA)">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <div class="testimonial-content">"L'équipe pédagogique est à l'écoute et très réactive."</div>
                    <div class="testimonial-author">LUCAS MARTEL</div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-icon" style="background: linear-gradient(135deg, #8B5CF6, #A855F7)">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="testimonial-content">"Une expérience formatrice et motivante, je recommande !"</div>
                    <div class="testimonial-author">MARINE LAFON</div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-icon" style="background: linear-gradient(135deg, #06B6D4, #0891B2)">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="testimonial-content">"Des contenus riches et adaptés à mon projet professionnel."</div>
                    <div class="testimonial-author">PIERRE GARNIER</div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-icon" style="background: linear-gradient(135deg, #C084FC, #EC4899)">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <div class="testimonial-content">"J'ai pu atteindre mes objectifs grâce à cette formation."</div>
                    <div class="testimonial-author">SARAH LEMOINE</div>
                </div>
            </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="cta-content">
                <h2>Prêt à développer vos compétences ?</h2>
                <p>Inscrivez-vous maintenant et bénéficiez de nos formations professionnelles</p>
                <a href="formations.php" class="cta primary-cta">Voir le catalogue</a>
            </div>
        </section>
    </main>

    <!-- Footer moderne -->
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