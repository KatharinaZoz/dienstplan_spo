<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/db.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

ini_set('display_errors', 0);
ini_set('display_startup_errors', 1);
ini_set('error_log', __DIR__ . '/database/error.log');
error_reporting(E_ALL);


$timeout = 1800; // 30 minutes

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
    header("Location: index.php?page=login");
    exit;
}

$_SESSION['last_activity'] = time();
if (isset($_GET['page'])) {
    $_SESSION['current_page'] = $_GET['page'];
}


/* ---------- ROUTING ---------- */
/*
 * Determine requested page via GET parameter.
 * If no page is provided, routing falls back to role-based defaults.
 */
$requestedPage = $_GET['page'] ?? null;

/*
 * Admin pages are explicitly whitelisted.
 * This flag is used to conditionally render admin navigation
 * and separate privileged views from regular dashboards.
 */
$isAdminPage = in_array($requestedPage, ['admin_users', 'admin_events']);


/*
 * Authentication & Access Control:
 * - Unauthenticated users are restricted to login/register.
 * - Authenticated users are routed based on role.
 * - Admin users default to admin overview.
 * - Regular users default to personal dashboard.
 */
if (!isset($_SESSION['user_id'])) {
    $page = ($requestedPage === 'register') ? 'register' : 'login';
} else if ($requestedPage !== null) {
    $page = $requestedPage;
} else {
    if (isset($_SESSION['is_admin']) && (int) $_SESSION['is_admin'] === 1) {
        $page = 'admin_events';
    } else if (isset($_SESSION['is_admin']) && (int) $_SESSION['is_admin'] === 0) {
        $page = 'dashboard';
    } else {
        $page = $requestedPage ?? 'dashboard';
    }
}

/* ---------- PAGE MAP ---------- */
$pages = [
    //auth pages
    'login' => 'page/auth/login.php',
    'register' => 'page/auth/register.php',
    'logout' => 'page/auth/logout.php',

    //dashboards
    'dashboard' => 'page/dashboard/dashboard_user/dashboard_user.php',

    //admin pages
    'admin_users' => 'page/dashboard/dashboard_admin/user/user_management.php',
    'admin_events' => 'page/dashboard/app_overview.php',
];
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($language) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DRK</title>

    <link rel="stylesheet" href="css/design_tokens.css">
    <link rel="stylesheet" href="css/includes.css">
    <link rel="stylesheet" href="css/auth_style.css">
    <link rel="stylesheet" href="css/calendar.css">

    <link rel="stylesheet" href="css/dashboard/dashboard.css">
    <link rel="stylesheet" href="css/dashboard/event/event_mngmt.css">
    <link rel="stylesheet" href="css/dashboard/event/event_edit.css">
    <link rel="stylesheet" href="css/dashboard/event/create_event.css">
    <link rel="stylesheet" href="css/dashboard/user/edit_user.css">
    <link rel="stylesheet" href="css/dashboard/user/user_search.css">

</head>

<body>

    <?php include 'includes/banner.php'; ?>

    <main>
        <?php
        /*
         * Page map acts as a controlled routing table.
         * Only predefined routes can be resolved to file paths,
         * preventing arbitrary file inclusion.
         */
        if (isset($pages[$page])) {
            if ($isAdminPage) {
                require 'page/dashboard/dashboard_admin/admin_nav.php';
            }
            require $pages[$page];
        } else {
            http_response_code(404);
            echo "Page not found";
        }
        ?>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>

</html>