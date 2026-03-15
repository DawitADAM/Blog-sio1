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
?>
