<?php
require 'php.php';

$sql = file_get_contents(__DIR__ . '/schema.sql');
$pdo->exec($sql);
echo "Base de données initialisée !";
