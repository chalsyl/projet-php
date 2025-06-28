<?php
require_once __DIR__ . '/../src/functions.php';
$formations = getFormations($pdo);
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inscription'])) {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $id_formation = (int)($_POST['id_formation'] ?? 0);
    if ($nom && $prenom && $email && $id_formation) {
        inscrireParticipant($pdo, $nom, $prenom, $email, $telephone, $id_formation, $genre);
        $message = 'Inscription réussie ! Vous recevrez un email de confirmation.';
    } else {
        $message = 'Merci de remplir tous les champs obligatoires.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
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
        <section id="inscription" class="inscription-container">
            <h2 class="inscription-title">Inscription en ligne</h2>
            <?php if ($message): ?>
                <div class="message<?= strpos($message, 'réussie') !== false ? ' success' : ' error' ?>"> <?= htmlspecialchars($message) ?> </div>
            <?php endif; ?>
            <form method="post" autocomplete="off">
                <input type="hidden" name="inscription" value="1">
                <label>Nom*<br><input type="text" name="nom" required></label>
                <label>Prénom*<br><input type="text" name="prenom" required></label>
                <label>Email*<br><input type="email" name="email" required></label>
                <label>Téléphone<br><input type="text" name="telephone"></label>
                <label>Genre*<br>
                    <select name="genre" required>
                        <option value="">Choisir...</option>
                        <option value="M">Masculin</option>
                        <option value="F">Féminin</option>
                    </select>
                </label>
                <label>Formation*<br>
                    <select name="id_formation" required>
                        <option value="">Choisir une formation</option>
                        <?php foreach ($formations as $formation): ?>
                            <option value="<?= $formation['id_formation'] ?>">
                                <?= htmlspecialchars($formation['intitule']) ?> (<?= date('d/m/Y', strtotime($formation['date_debut'])) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <button type="submit">S'inscrire</button>
            </form>
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
