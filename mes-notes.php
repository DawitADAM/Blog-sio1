<?php
require 'session.php';
requireRole('etudiant');
require 'php.php';

$userId = userId();

$stmt = $pdo->prepare('SELECT nom, prenom FROM Utilisateur WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();

$stmt = $pdo->prepare(
    'SELECT matiere, note, appreciation, date_saisie FROM Notes WHERE etudiant_id = ? ORDER BY matiere'
);
$stmt->execute([$userId]);
$notes = $stmt->fetchAll();

$moyenne = null;
if ($notes) {
    $moyenne = array_sum(array_column($notes, 'note')) / count($notes);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes notes — Portfolio BTS SIO1</title>
    <link rel="stylesheet" href="style/styles.css">
    <style>
        main { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #007bff; color: #fff; }
        tr:nth-child(even) { background: #f9f9f9; }
        .moyenne { margin-top: 16px; font-weight: bold; font-size: 1.1rem; }
        .no-notes { color: #888; margin-top: 20px; }
    </style>
</head>
<body>
<?php require 'header.php'; ?>
<main>
    <h2>Mes notes — <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h2>

    <?php if (empty($notes)): ?>
        <p class="no-notes">Aucune note disponible pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Matière</th>
                    <th>Note /20</th>
                    <th>Appréciation</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($notes as $n): ?>
                <tr>
                    <td><?= htmlspecialchars($n['matiere']) ?></td>
                    <td><?= number_format($n['note'], 2) ?></td>
                    <td><?= htmlspecialchars($n['appreciation'] ?? '') ?></td>
                    <td><?= htmlspecialchars($n['date_saisie']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <p class="moyenne">Moyenne générale : <?= number_format($moyenne, 2) ?> / 20</p>
    <?php endif; ?>
</main>
</body>
</html>
