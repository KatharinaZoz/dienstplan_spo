<?php declare(strict_types=1);
include_once __DIR__ . "/../src/UserRepository.php";
include_once __DIR__ . "/email.php";
include_once __DIR__ . "/validation.php";

function register_user(PDO $pdo): bool
{
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }


    if (!$pdo instanceof PDO) {
        throw new Exception("Database connection not valid!");
    }

    validate_input_user(true);


    if (get_user_by_email($pdo, $_POST['email']) !== []) {
        echo "user already exists";
    }

    $new_user = create_user($pdo);


    if ($new_user === null) {
        echo "database error!";
        return false;
    }
    session_regenerate_id(true);
    set_user_session($new_user);
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    //send_registration_email();

    return true;
}



function send_registration_email()
{
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $subject = "Registrierung DRK Portal XY";
    $body = "Hallo $first_name $last_name! \n
            Hiermit bestätigen wir dir die Registrierung im DRK Portal XY. \n
            Mit freundlichen Grüßen\n
            Ihr DRK Team!";

    sendEmail($_POST['email'], $first_name + ' ' + $last_name, $subject, $body);

}

?>