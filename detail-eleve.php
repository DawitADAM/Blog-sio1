<?php
require 'session.php';
requireRole('prof', 'admin');
require 'php.php';

$eleveId = (int)($_GET['id'] ?? 0);
if ($eleveId <= 0) {
    header('Location: notes-classe.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id, nom, prenom, mail FROM Utilisateur WHERE id = ? AND role = \'etudiant\'');
$stmt->execute([$eleveId]);
$eleve = $stmt->fetch();

if (!$eleve) {
    http_response_code(404);
    die('<p>Élève introuvable.</p>');
}

$stmt = $pdo->prepare(
    'SELECT matiere, note, appreciation, date_saisie FROM Notes WHERE etudiant_id = ? ORDER BY matiere'
);
$stmt->execute([$eleveId]);
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
    <title>Détail élève — Portfolio BTS SIO1</title>
    <link rel="stylesheet" href="style/styles.css">
    <style>
        main { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #007bff; color: #fff; }
        tr:nth-child(even) { background: #f9f9f9; }
        .moyenne { margin-top: 16px; font-weight: bold; font-size: 1.1rem; }
        .back-link { display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
<?php require 'header.php'; ?>
<main>
    <h2>Notes de <?= htmlspecialchars($eleve['prenom'] . ' ' . $eleve['nom']) ?></h2>
    <p><em><?= htmlspecialchars($eleve['mail']) ?></em></p>

    <?php if (empty($notes)): ?>
        <p>Aucune note disponible.</p>
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
        <p class="moyenne">Moyenne : <?= number_format($moyenne, 2) ?> / 20</p>
    <?php endif; ?>

    <a class="back-link" href="notes-classe.php">&larr; Retour à la liste</a>
</main>
</body>
</html>
