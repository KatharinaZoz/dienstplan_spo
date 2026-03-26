<?php

// your existing logout logic
$_SESSION = [];
session_destroy();

// optional: delete session cookie
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// redirect to login
header("Location: index.php?page=login");
exit;
?>