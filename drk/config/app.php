<?php
declare(strict_types=1);

/* ---------- SESSION ---------- */
ini_set('session.cookie_httponly', 1);
//ini_set('session.cookie_secure', 1);  //if https is being used
ini_set('session.use_strict_mode', 1);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    if (empty($_SESSION['csrf-token'])) {
        $_SESSION['csrf-token'] = bin2hex(random_bytes(32));
    }
}

/* ---------- BASE PATH ---------- */
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', ''); // optional if deployed in subfolder

/* ---------- LANGUAGE ---------- */
if (isset($_GET['langID'])) {
    $_SESSION['langID'] = $_GET['langID'];
}

$language = $_SESSION['langID'] ?? 'de';

/* ---------- LOAD TRANSLATIONS ---------- */
$langFile = BASE_PATH . '/../locale/' . $language . '.php';

if (!file_exists($langFile)) {
    $langFile = BASE_PATH . '/locale/de.php';
}

$langArray = require $langFile;
