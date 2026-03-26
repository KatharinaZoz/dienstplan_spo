<?php
// config/db.php
$host = '127.0.0.1'; // or 'localhost'
$db = 'drk_auth';
$user = 'drk_user';            // your DB username
$pass = '1';   // your DB password
$charset = 'utf8mb4';
//die("DB USER=" . $user . " HOST=" . $host);

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // throw exceptions on errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // fetch associative arrays
        PDO::ATTR_EMULATE_PREPARES => false, // use real prepared statements
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
