<?php
$host    = getenv('MYSQLHOST');
$db      = getenv('MYSQLDATABASE');
$user    = getenv('MYSQLUSER');
$pass    = getenv('MYSQLPASSWORD');
$port    = getenv('MYSQLPORT') ?: '3306';
$charset = 'utf8mb4';

$dsn = "mysql:host=mysql.railway.internal;port=3306;dbname=$db;charset=$charset";$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Erreur DB : " . $e->getMessage());
}

// Init schema if tables don't exist
$pdo->exec("
CREATE TABLE IF NOT EXISTS Utilisateur (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  prenom VARCHAR(100) NOT NULL,
  mail VARCHAR(255) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin', 'prof', 'etudiant') NOT NULL DEFAULT 'etudiant',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS Notes (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  matiere VARCHAR(100) NOT NULL,
  note DECIMAL(4,2) NOT NULL,
  appreciation TEXT,
  date_saisie DATE DEFAULT (CURRENT_DATE),
  FOREIGN KEY (etudiant_id) REFERENCES Utilisateur(id) ON DELETE CASCADE
);

INSERT IGNORE INTO Utilisateur (nom, prenom, mail, password_hash, role) VALUES
('Dupont', 'Jean', 'jean.dupont@esicad.fr', SHA2('motdepasse123', 256), 'etudiant'),
('Martin', 'Sophie', 'sophie.martin@esicad.fr', SHA2('motdepasse123', 256), 'prof'),
('Admin', 'Root', 'admin@esicad.fr', SHA2('admin123', 256), 'admin');
");
?>
