<?php
// Fichier pour regrouper les fonctions PHP du projet

require_once __DIR__ . '/db.php';

function getFormations(PDO $pdo) {
    $sql = 'SELECT id_formation, domaine, intitule, lieu, date_debut, duree, montant, fiche_programme FROM formation ORDER BY date_debut DESC';
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAnimateursByFormation(PDO $pdo, $id_formation) {
    $sql = "SELECT a.nom, a.prenom FROM animateur a
            JOIN formation_animateur fa ON a.id_animateur = fa.id_animateur
            WHERE fa.id_formation = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_formation]);
    return $stmt->fetchAll();
}

function getFicheProgramme(PDO $pdo, $id_formation) {
    $stmt = $pdo->prepare('SELECT f.*, GROUP_CONCAT(CONCAT(a.prenom, " ", a.nom) SEPARATOR "||") as animateurs 
                          FROM formation f 
                          LEFT JOIN formation_animateur fa ON f.id_formation = fa.id_formation 
                          LEFT JOIN animateur a ON fa.id_animateur = a.id_animateur 
                          WHERE f.id_formation = ? 
                          GROUP BY f.id_formation');
    $stmt->execute([$id_formation]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function inscrireParticipant(PDO $pdo, $nom, $prenom, $email, $telephone, $id_formation, $genre = null) {
    try {
        $pdo->beginTransaction();
        
        // Vérifier si le participant existe déjà
        $stmt = $pdo->prepare('SELECT id_participant FROM participant WHERE email = ?');
        $stmt->execute([$email]);
        $participant = $stmt->fetch();
        
        if ($participant) {
            $id_participant = $participant['id_participant'];
            // Mettre à jour le genre si fourni
            if ($genre !== null) {
                $stmt = $pdo->prepare('UPDATE participant SET genre = ? WHERE id_participant = ?');
                $stmt->execute([$genre, $id_participant]);
            }
        } else {
            // Créer un nouveau participant
            $stmt = $pdo->prepare('INSERT INTO participant (nom, prenom, email, telephone, genre) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$nom, $prenom, $email, $telephone, $genre]);
            $id_participant = $pdo->lastInsertId();
        }
        
        // Créer l'inscription
        $stmt = $pdo->prepare('INSERT INTO inscription (id_participant, id_formation) VALUES (?, ?)');
        $stmt->execute([$id_participant, $id_formation]);
        
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}
