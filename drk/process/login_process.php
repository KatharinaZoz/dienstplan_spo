<?php declare(strict_types=1);
include_once __DIR__ . "/../src/UserRepository.php";
include_once __DIR__ . "/validation.php";
function login_user(PDO $pdo): bool
{
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }


    if (!$pdo instanceof PDO) {
        throw new Exception("Database connection not valid!");
    }
    //check required inputs
    $email = trim($_POST["email"]);
    if (empty($email)) {
        die("email is empty");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address!");
    } else if (empty($_POST["password"])) {
        die("Enter a Password pussy!");
    }

    $user = get_user_by_email($pdo, $email);
    if ($user == []) {
        echo "User is non existent";
        return false;
    }

    //set the users session id and if they are admin or not
    if (!password_verify($_POST['password'], $user['passwd_hash'])) {
        return false;
    }
    session_regenerate_id(true);
    set_user_session($user);
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    return true;
}
?>