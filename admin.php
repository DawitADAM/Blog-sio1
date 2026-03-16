<?php
require 'session.php';
requireRole('admin');
require 'php.php';

$success = '';
$error   = '';

// Ajout d'une note
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_note') {
        $eleveId    = (int)($_POST['etudiant_id'] ?? 0);
        $matiere    = trim($_POST['matiere'] ?? '');
        $note       = (float)($_POST['note'] ?? 0);
        $apprec     = trim($_POST['appreciation'] ?? '');

        if ($eleveId > 0 && $matiere !== '' && $note >= 0 && $note <= 20) {
            $stmt = $pdo->prepare(
                'INSERT INTO Notes (etudiant_id, matiere, note, appreciation) VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([$eleveId, $matiere, $note, $apprec]);
            $success = 'Note ajoutée avec succès.';
        } else {
            $error = 'Données invalides. La note doit être entre 0 et 20.';
        }
    } elseif ($_POST['action'] === 'delete_note') {
        $noteId = (int)($_POST['note_id'] ?? 0);
        if ($noteId > 0) {
            $stmt = $pdo->prepare('DELETE FROM Notes WHERE id = ?');
            $stmt->execute([$noteId]);
            $success = 'Note supprimée.';
        }
    }
}

// Récupération de tous les élèves
$eleves = $pdo->query(
    'SELECT id, nom, prenom FROM Utilisateur WHERE role = \'etudiant\' ORDER BY nom, prenom'
)->fetchAll();

// Récupération de toutes les notes avec nom de l'élève
$allNotes = $pdo->query(
    'SELECT n.id, u.nom, u.prenom, n.matiere, n.note, n.appreciation, n.date_saisie
     FROM Notes n
     JOIN Utilisateur u ON u.id = n.etudiant_id
     ORDER BY u.nom, u.prenom, n.matiere'
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration — Portfolio BTS SIO1</title>
    <link rel="stylesheet" href="style/styles.css">
    <style>
        main { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        section { margin-bottom: 40px; }
        h2 { border-bottom: 2px solid #007bff; padding-bottom: 8px; }
        .form-row { display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; margin-bottom: 16px; }
        .form-row label { display: block; margin-bottom: 4px; font-weight: bold; }
        .form-row input, .form-row select, .form-row textarea { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .form-row textarea { resize: vertical; }
        .btn { padding: 8px 18px; border: none; border-radius: 4px; cursor: pointer; font-size: .95rem; }
        .btn-primary { background: #007bff; color: #fff; }
        .btn-primary:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; color: #fff; }
        .btn-danger:hover { background: #a71d2a; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 9px; text-align: left; }
        th { background: #007bff; color: #fff; }
        tr:nth-child(even) { background: #f9f9f9; }
        .msg-success { color: green; margin-bottom: 14px; }
        .msg-error   { color: red;   margin-bottom: 14px; }
    </style>
</head>
<body>
<?php require 'header.php'; ?>
<main>
    <h1>Administration</h1>

    <?php if ($success): ?><p class="msg-success"><?= htmlspecialchars($success) ?></p><?php endif; ?>
    <?php if ($error):   ?><p class="msg-error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <!-- Formulaire ajout note -->
    <section>
        <h2>Ajouter une note</h2>
        <form method="post" action="admin.php">
            <input type="hidden" name="action" value="add_note">
            <div class="form-row">
                <div>
                    <label for="etudiant_id">Élève</label>
                    <select id="etudiant_id" name="etudiant_id" required>
                        <option value="">— Sélectionner —</option>
                        <?php foreach ($eleves as $e): ?>
                            <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nom'] . ' ' . $e['prenom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="matiere">Matière</label>
                    <input type="text" id="matiere" name="matiere" value="U5 BTS SIO SISR" readonly style="background:#f0f0f0; cursor:default;">
                </div>
                <div>
                    <label for="note">Note /20</label>
                    <input type="number" id="note" name="note" step="0.01" min="0" max="20" required style="width:90px">
                </div>
                <div style="flex:1">
                    <label for="appreciation">Appréciation</label>
                    <textarea id="appreciation" name="appreciation" rows="2" style="width:100%"></textarea>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </form>
    </section>

    <!-- Liste de toutes les notes -->
    <section>
        <h2>Toutes les notes</h2>
        <?php if (empty($allNotes)): ?>
            <p>Aucune note enregistrée.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Élève</th>
                        <th>Matière</th>
                        <th>Note /20</th>
                        <th>Appréciation</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($allNotes as $n): ?>
                    <tr>
                        <td><?= htmlspecialchars($n['nom'] . ' ' . $n['prenom']) ?></td>
                        <td><?= htmlspecialchars($n['matiere']) ?></td>
                        <td><?= number_format($n['note'], 2) ?></td>
                        <td><?= htmlspecialchars($n['appreciation'] ?? '') ?></td>
                        <td><?= htmlspecialchars($n['date_saisie']) ?></td>
                        <td>
                            <form method="post" action="admin.php" onsubmit="return confirm('Supprimer cette note ?')">
                                <input type="hidden" name="action" value="delete_note">
                                <input type="hidden" name="note_id" value="<?= $n['id'] ?>">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
