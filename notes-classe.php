<?php
require 'session.php';
requireRole('prof', 'admin');
require 'php.php';

$stmt = $pdo->query(
    'SELECT u.id, u.nom, u.prenom,
            COUNT(n.id) AS nb_notes,
            AVG(n.note) AS moyenne
     FROM Utilisateur u
     LEFT JOIN Notes n ON n.etudiant_id = u.id
     WHERE u.role = \'etudiant\'
     GROUP BY u.id, u.nom, u.prenom
     ORDER BY u.nom, u.prenom'
);
$eleves = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Notes de la classe — Portfolio BTS SIO1</title>
    <link rel="stylesheet" href="style/styles.css">
    <style>
        main { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #007bff; color: #fff; }
        tr:nth-child(even) { background: #f9f9f9; }
        a.btn-detail { color: #007bff; text-decoration: none; }
        a.btn-detail:hover { text-decoration: underline; }
    </style>
</head>
<body>
<?php require 'header.php'; ?>
<main>
    <h2>Notes de la classe</h2>
    <?php if (empty($eleves)): ?>
        <p>Aucun élève trouvé.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Nb notes</th>
                    <th>Moyenne /20</th>
                    <th>Détail</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($eleves as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['nom']) ?></td>
                    <td><?= htmlspecialchars($e['prenom']) ?></td>
                    <td><?= (int)$e['nb_notes'] ?></td>
                    <td><?= $e['moyenne'] !== null ? number_format($e['moyenne'], 2) : '—' ?></td>
                    <td><a class="btn-detail" href="detail-eleve.php?id=<?= (int)$e['id'] ?>">Voir le détail</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
</body>
</html>
