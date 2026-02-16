<?php
require 'php.php';

$stmt = $pdo->query('SELECT nom, prenom, mail FROM Utilisateur');
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact</title>
    <link rel="stylesheet" href="style/styles.css">
</head>
<body>
<header>
    <h1>Contact</h1>
    <div>
        <a href="index.php" class="headerButton">Accueil</a>
        <a href="eleves.php" class="headerButton">Élèves</a>
        <a href="esicad.html" class="headerButton">Conformité et Normes</a>
        <a href="user.php" class="headerButton">Contact</a>
    </div>
</header>

<h2>Liste des élèves (exemple)</h2>
<ul>
<?php foreach($users as $u): ?>
    <li><?= htmlspecialchars($u['nom'].' '.$u['prenom']).' - '.$u['mail'] ?></li>
<?php endforeach; ?>
</ul>

</body>
</html>
