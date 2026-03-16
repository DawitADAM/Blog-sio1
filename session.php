<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireLogin() {
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function requireRole(string ...$roles) {
    requireLogin();
    if (!in_array($_SESSION['user_role'], $roles, true)) {
        http_response_code(403);
        die('<p>Accès interdit. Vous n\'avez pas les droits nécessaires.</p>');
    }
}

function isLoggedIn(): bool {
    return !empty($_SESSION['user_id']);
}

function userRole(): string {
    return $_SESSION['user_role'] ?? '';
}

function userId(): int {
    return (int)($_SESSION['user_id'] ?? 0);
}
