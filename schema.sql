CREATE TABLE IF NOT EXISTS Utilisateur (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  prenom VARCHAR(100) NOT NULL,
  mail VARCHAR(255) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin', 'prof', 'etudiant') NOT NULL DEFAULT 'etudiant',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT IGNORE INTO Utilisateur (nom, prenom, mail, password_hash, role) VALUES
('Dupont', 'Jean', 'jean.dupont@esicad.fr', SHA2('motdepasse123', 256), 'etudiant'),
('Martin', 'Sophie', 'sophie.martin@esicad.fr', SHA2('motdepasse123', 256), 'prof'),
('Admin', 'Root', 'admin@esicad.fr', SHA2('admin123', 256), 'admin');
