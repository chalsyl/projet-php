<div id="menu">
<ul>
    <li><a href="statistiques.php" <?= basename($_SERVER['PHP_SELF']) == 'statistiques.php' ? 'class="active"' : '' ?>>Statistiques</a></li>
    <li><a href="liste_participants.php" <?= basename($_SERVER['PHP_SELF']) == 'liste_participants.php' ? 'class="active"' : '' ?>>Liste des participants</a></li>
    <li><a href="inscriptions_par_formation.php" <?= basename($_SERVER['PHP_SELF']) == 'inscriptions_par_formation.php' ? 'class="active"' : '' ?>>Inscriptions par formation</a></li>
    <li><a href="formations.php" <?= basename($_SERVER['PHP_SELF']) == 'formations.php' ? 'class="active"' : '' ?>>Gestion des formations</a></li>
    <li><a href="animateurs.php" <?= basename($_SERVER['PHP_SELF']) == 'animateurs.php' ? 'class="active"' : '' ?>>Gestion des animateurs</a></li>
    <li><a href="sortie.php">Déconnexion</a></li>
</ul>
</div>
<link rel="stylesheet" href="admin.css">