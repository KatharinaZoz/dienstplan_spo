<?php require_once __DIR__ . '/login_process.php';
require_once __DIR__ . '/../src/ApplicationRepository.php';

function validate_user(bool $validate_admin, PDO $pdo): bool
{
    //check for session hijacking
    if (isset($_SESSION["user_id"])) {
        if (!isset($_SESSION["user_agent"]) || $_SESSION["user_agent"] !== $_SERVER["HTTP_USER_AGENT"]) {

            //possible hijacked session
            session_unset();
            session_destroy();

            redirect_login();
        }
    } else {
        redirect_login();
    }

    //check if user in cookies is admin
    if ($validate_admin) {
        $user = get_user_by_id($pdo);
        if (!$user) {
            return false;
        } else if ((int) $user['is_admin'] === 0) {
            http_response_code(403);
            exit("Forbidden");
        }
    }
    return true;
}

function validate_input_user(bool $checkAll): bool
{
    //check required inputs
    $full_required_fields = ['first_name', 'last_name', 'email', 'birthday', 'local_ass', 'password_create', 'password_repeat', 'qualifications', 'csrf_token'];
    $modified_required_fields = ['first_name', 'last_name', 'email', 'birthday', 'local_ass', 'qualification', 'csrf_token'];
    $qualifications = ['', 'ersthelfer', 'pflege', 'sanitäter', 'rettungshelfer', 'rettungssanitäter', 'rettungsassistent', 'notfallsanitäter', 'arzt', 'notarzt'];

    $required_fields = $checkAll ? $full_required_fields : $modified_required_fields;

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            error_log("input field $field invalid");
            return false;
        }
    }

    if (!in_array($_POST['qualification'], $qualifications)) {
        error_log("invalid qualification");
        return false;
    } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        error_log("email invalid");
        return false;
    }
    return true;
}

function redirect_login()
{
    header("Location: /index.php?page=login");
    exit;

}
?>