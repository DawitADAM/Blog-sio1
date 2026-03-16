<?php
require 'session.php';

if (isLoggedIn()) {
    $role = userRole();
    if ($role === 'admin') header('Location: admin.php');
    elseif ($role === 'prof') header('Location: notes-classe.php');
    else header('Location: mes-notes.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'php.php';

    $mail = trim($_POST['mail'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($mail === '' || $password === '') {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        $stmt = $pdo->prepare('SELECT id, nom, prenom, role, password_hash, must_change_password FROM Utilisateur WHERE mail = ?');
        $stmt->execute([$mail]);
        $user = $stmt->fetch();

        if ($user && $user['password_hash'] === hash('sha256', $password)) {
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_nom']   = $user['nom'];
            $_SESSION['user_prenom'] = $user['prenom'];
            $_SESSION['user_role']  = $user['role'];

            if ($user['role'] === 'etudiant' && $user['must_change_password']) {
                header('Location: change-password.php');
            } elseif ($user['role'] === 'admin') {
                header('Location: admin.php');
            } elseif ($user['role'] === 'prof') {
                header('Location: notes-classe.php');
            } else {
                header('Location: mes-notes.php');
            }
            exit;
        } else {
            $error = 'Email ou mot de passe incorrect.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion — Portfolio BTS SIO1</title>
    <link rel="stylesheet" href="style/styles.css">
    <style>
        .login-container { max-width: 400px; margin: 60px auto; padding: 30px; background: #fff; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,.1); }
        .login-container h2 { margin-bottom: 24px; text-align: center; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-login { width: 100%; padding: 12px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; }
        .btn-login:hover { background: #0056b3; }
        .error { color: red; margin-bottom: 16px; text-align: center; }
    </style>
</head>
<body>
<?php require 'header.php'; ?>
<main>
    <div class="login-container">
        <h2>Connexion</h2>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            <div class="form-group">
                <label for="mail">Adresse e-mail</label>
                <input type="email" id="mail" name="mail" required autofocus value="<?= htmlspecialchars($_POST['mail'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Se connecter</button>
        </form>
    </div>
</main>
</body>
</html>
