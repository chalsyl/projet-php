<?php
session_start();
if(isset($_SESSION['login'])){
    header("location:formations.php");
    exit();
}
$conn = new mysqli('localhost', 'root', '', 'catalogue_formations');
if ($conn->connect_error) {
    die('Erreur de connexion à la base de données : ' . $conn->connect_error);
}
$message = '';
if(isset($_POST['Bconnexion'])){
    $login = $_POST['login'];
    $mdp = $_POST['mdp'];
    $mdpHash = sha1($mdp);
    $sql = "SELECT count(*) as nb FROM admin WHERE login=? AND mdp=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $login, $mdpHash);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    if($row['nb'] == 1){
        $_SESSION['login'] = $login;
        header("location:formations.php");
        exit();
    } else {
        $message = "<span style='color:red'>Login ou mot de passe incorrect</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        #canvas-counailles {
            position: fixed;
            top: 0; left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 0;
            pointer-events: none;
        }
        .admin-content { position: relative; z-index: 1; }
    </style>
</head>
<body class="admin-ninja-bg">
<div class="admin-content">
    <div id="menu">
        <ul>
            <li><a href="../public/index.php">Accueil</a></li>
            <li><a href="../public/formations.php">Catalogue des formations</a></li>
        </ul>
    </div>
    <form method="post" action="index.php" style="max-width:400px;margin:2em auto;">
        <h2>Connexion administrateur</h2>
        <?= $message ?>
        <div class="field">
            <label>Login</label>
            <input type="text" name="login" required>
        </div>
        <div class="field">
            <label>Mot de passe</label>
            <input type="password" name="mdp" required>
        </div>
        <button type="submit" name="Bconnexion">Se connecter</button>
    </form>
</div>
</body>
</html>
