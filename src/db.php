<?php
// Connexion PDO à la base de données MySQL avec gestion d'erreurs améliorée
$host = 'localhost';
$db   = 'catalogue_formations'; // À adapter selon le nom de votre base
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_PERSISTENT         => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
    PDO::ATTR_TIMEOUT            => 30,
    PDO::MYSQL_ATTR_RECONNECT    => true
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Test de la connexion
    $pdo->query('SELECT 1');
    
} catch (PDOException $e) {
    // Log l'erreur pour debug
    error_log('Erreur de connexion PDO: ' . $e->getMessage());
    
    // Message d'erreur utilisateur
    die('
    <div style="
        background: linear-gradient(135deg, #6B46C1, #8B5CF6);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin: 2rem;
        font-family: Arial, sans-serif;
        text-align: center;
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    ">
        <h2>🔧 Configuration de la base de données requise</h2>
        <p><strong>Erreur de connexion :</strong> ' . htmlspecialchars($e->getMessage()) . '</p>
        <div style="background: rgba(255,255,255,0.1); padding: 1.5rem; border-radius: 8px; margin: 1rem 0; text-align: left;">
            <h3>📋 Étapes à suivre :</h3>
            <ol style="line-height: 1.8;">
                <li><strong>Démarrez XAMPP</strong> et assurez-vous que MySQL est en cours d\'exécution</li>
                <li><strong>Ouvrez phpMyAdmin</strong> (http://localhost/phpmyadmin)</li>
                <li><strong>Créez la base de données</strong> : <code style="background: rgba(0,0,0,0.3); padding: 0.2rem 0.5rem; border-radius: 4px;">catalogue_formations</code></li>
                <li><strong>Importez le fichier SQL</strong> : <code style="background: rgba(0,0,0,0.3); padding: 0.2rem 0.5rem; border-radius: 4px;">data/structure_catalogue.sql</code></li>
                <li><strong>Vérifiez les paramètres</strong> dans <code style="background: rgba(0,0,0,0.3); padding: 0.2rem 0.5rem; border-radius: 4px;">src/db.php</code></li>
            </ol>
        </div>
        <p style="margin-top: 1.5rem;">
            <a href="http://localhost/phpmyadmin" target="_blank" style="
                background: rgba(255,255,255,0.2);
                color: white;
                padding: 0.75rem 1.5rem;
                text-decoration: none;
                border-radius: 6px;
                display: inline-block;
                margin: 0.5rem;
                transition: all 0.3s;
            ">🔗 Ouvrir phpMyAdmin</a>
        </p>
    </div>
    ');
}