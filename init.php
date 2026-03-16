<?php
require 'php.php';

$errors = [];

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS Utilisateur (
      id INT PRIMARY KEY AUTO_INCREMENT,
      nom VARCHAR(100) NOT NULL,
      prenom VARCHAR(100) NOT NULL,
      mail VARCHAR(255) UNIQUE NOT NULL,
      password_hash VARCHAR(255) NOT NULL,
      role ENUM('admin', 'prof', 'etudiant') NOT NULL DEFAULT 'etudiant',
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✅ Table Utilisateur OK<br>";
} catch (\PDOException $e) {
    $errors[] = "Utilisateur: " . $e->getMessage();
    echo "❌ Table Utilisateur : " . $e->getMessage() . "<br>";
}

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS Notes (
      id INT PRIMARY KEY AUTO_INCREMENT,
      etudiant_id INT NOT NULL,
      matiere VARCHAR(100) NOT NULL,
      note DECIMAL(4,2) NOT NULL,
      appreciation TEXT,
      date_saisie DATE NULL DEFAULT NULL,
      FOREIGN KEY (etudiant_id) REFERENCES Utilisateur(id) ON DELETE CASCADE
    )");
    echo "✅ Table Notes OK<br>";
} catch (\PDOException $e) {
    $errors[] = "Notes: " . $e->getMessage();
    echo "❌ Table Notes : " . $e->getMessage() . "<br>";
}

try {
    $pdo->exec("INSERT IGNORE INTO Utilisateur (nom, prenom, mail, password_hash, role) VALUES
    ('Dupont', 'Jean', 'jean.dupont@esicad.fr', SHA2('motdepasse123', 256), 'etudiant'),
    ('Martin', 'Sophie', 'sophie.martin@esicad.fr', SHA2('motdepasse123', 256), 'prof'),
    ('Admin', 'Root', 'admin@esicad.fr', SHA2('admin123', 256), 'admin')");
    echo "✅ Utilisateurs insérés OK<br>";
} catch (\PDOException $e) {
    $errors[] = "Insert: " . $e->getMessage();
    echo "❌ Insert utilisateurs : " . $e->getMessage() . "<br>";
}

if (empty($errors)) {
    echo "<br><strong>Base de données initialisée avec succès !</strong>";
} else {
    echo "<br><strong>Terminé avec des erreurs.</strong>";
}
