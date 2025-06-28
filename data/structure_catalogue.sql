-- Script SQL pour la base de données du catalogue de formations continues

CREATE TABLE formation (
    id_formation INT AUTO_INCREMENT PRIMARY KEY,
    domaine VARCHAR(100) NOT NULL,
    intitule VARCHAR(255) NOT NULL,
    lieu VARCHAR(100) NOT NULL,
    date_debut DATE NOT NULL,
    duree INT NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    fiche_programme TEXT
);

CREATE TABLE animateur (
    id_animateur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL
);

CREATE TABLE formation_animateur (
    id_formation INT NOT NULL,
    id_animateur INT NOT NULL,
    PRIMARY KEY (id_formation, id_animateur),
    FOREIGN KEY (id_formation) REFERENCES formation(id_formation) ON DELETE CASCADE,
    FOREIGN KEY (id_animateur) REFERENCES animateur(id_animateur) ON DELETE CASCADE
);

CREATE TABLE participant (
    id_participant INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telephone VARCHAR(20),
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    genre VARCHAR(20)
);

CREATE TABLE inscription (
    id_inscription INT AUTO_INCREMENT PRIMARY KEY,
    id_participant INT NOT NULL,
    id_formation INT NOT NULL,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_participant) REFERENCES participant(id_participant) ON DELETE CASCADE,
    FOREIGN KEY (id_formation) REFERENCES formation(id_formation) ON DELETE CASCADE
);

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    mdp VARCHAR(255) NOT NULL
);

INSERT INTO admin (login, mdp) VALUES ('admin',SHA1('admin'));
