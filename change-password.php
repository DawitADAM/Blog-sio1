<?php
require 'session.php';
requireRole('etudiant');
require 'php.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new  = $_POST['new_password']     ?? '';
    $conf = $_POST['confirm_password'] ?? '';

    // Security rules
    $valid = true;
    if (strlen($new) < 8) {
        $error = 'Le mot de passe doit contenir au moins 8 caractères.';
        $valid = false;
    } elseif (!preg_match('/[A-Z]/', $new)) {
        $error = 'Le mot de passe doit contenir au moins une lettre majuscule.';
        $valid = false;
    } elseif (!preg_match('/[a-z]/', $new)) {
        $error = 'Le mot de passe doit contenir au moins une lettre minuscule.';
        $valid = false;
    } elseif (!preg_match('/[0-9]/', $new)) {
        $error = 'Le mot de passe doit contenir au moins un chiffre.';
        $valid = false;
    } elseif (!preg_match('/[\W_]/', $new)) {
        $error = 'Le mot de passe doit contenir au moins un caractère spécial (!@#$%^&*…).';
        $valid = false;
    } elseif ($new !== $conf) {
        $error = 'Les deux mots de passe ne correspondent pas.';
        $valid = false;
    }

    if ($valid) {
        $stmt = $pdo->prepare(
            'UPDATE Utilisateur SET password_hash = SHA2(?, 256), must_change_password = 0 WHERE id = ?'
        );
        $stmt->execute([$new, userId()]);
        header('Location: mes-notes.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier votre mot de passe — Portfolio BTS SIO1</title>
    <link rel="stylesheet" href="style/styles.css">
    <style>
        .pwd-container { max-width: 460px; margin: 60px auto; padding: 30px; background: #fff; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,.1); }
        .pwd-container h2 { margin-bottom: 8px; text-align: center; }
        .pwd-container .subtitle { color: #666; font-size: .9rem; text-align: center; margin-bottom: 24px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .rules { background: #f0f4ff; border-left: 4px solid #007bff; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; font-size: .88rem; }
        .rules ul { margin: 6px 0 0 16px; padding: 0; }
        .rules li { margin-bottom: 4px; }
        .btn-submit { width: 100%; padding: 12px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; }
        .btn-submit:hover { background: #0056b3; }
        .error   { color: #c0392b; background: #fdecea; padding: 10px; border-radius: 4px; margin-bottom: 16px; text-align: center; }
    </style>
</head>
<body>
<?php require 'header.php'; ?>
<main>
    <div class="pwd-container">
        <h2>Bienvenue !</h2>
        <p class="subtitle">Pour des raisons de sécurité, vous devez définir votre propre mot de passe avant de continuer.</p>

        <div class="rules">
            <strong>Le mot de passe doit contenir :</strong>
            <ul>
                <li>Au moins <strong>8 caractères</strong></li>
                <li>Au moins une <strong>lettre majuscule</strong> (A–Z)</li>
                <li>Au moins une <strong>lettre minuscule</strong> (a–z)</li>
                <li>Au moins un <strong>chiffre</strong> (0–9)</li>
                <li>Au moins un <strong>caractère spécial</strong> (!@#$%…)</li>
            </ul>
        </div>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" action="change-password.php">
            <div class="form-group">
                <label for="new_password">Nouveau mot de passe</label>
                <input type="password" id="new_password" name="new_password" required autofocus>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn-submit">Enregistrer et continuer</button>
        </form>
    </div>
</main>
</body>
</html>
