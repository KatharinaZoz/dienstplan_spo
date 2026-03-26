<?php include_once __DIR__ . "/admin_dashboard_process.php";
include_once __DIR__ . "/../src/UserRepository.php";
include_once __DIR__ . "/register_process.php";
include_once __DIR__ . "/validation.php";

function navigate_admin_menu(PDO $pdo)
{
    validate_user(true, $pdo);
    switch ($_GET['admin_nav']) {
        case 'users':
            header("Location: index.php?page=admin_users");
            exit;

        case 'app':
            header("Location: index.php?page=admin_events");
            exit;
    }
}

function change_user_data(PDO $pdo, $data)
{
    validate_user(true, $pdo);
    $error = [];
    $success = false;

    try {
        $success = validate_input_user(false);
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
    try {
        $success = update_user($pdo);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $error['db'] = "Something went wrong while saving changes.";
    }
    if (!$success) {
        $error["success"] = "Something went wrong while saving changes.";
    }
    return $error;

}

function update_event_data(PDO $pdo)
{
    $event_id = $_POST["event_id"];
    validate_user(true, $pdo);
    if (!check_event_by_id($pdo, $event_id)) {
        error_log("Invalid event data");
        return;
    }
    $success = update_event($pdo);
    $success &= update_participants($pdo);
    return $success;
}

function delete_user_process(PDO $pdo)
{
    validate_user(true, $pdo);
    $error = [];
    $success = false;
    $id = (int) $_POST["user_id"];
    try {
        $success = delete_user($pdo, $id);
    } catch (Exception $e) {
        error_log($e->getMessage());
        $error["database"] = "Something went wrong while deleting the user.";
    }
    if (!$success) {
        $error["database"] = "Something went wrong while deleting the user.";
    }
    return $error;
}

function apply_to_event(PDO $pdo)
{
    validate_user(false, $pdo);
    if (!check_event_by_id($pdo, $_POST["event_id"])) {
        $error["event_gone"] = "Event does not seem to exist.";

        return $error;
    }
    $error = [];
    $success = false;
    $id = (int) $_SESSION["user_id"];
    try {
        $success = apply_user($pdo, $id);
    } catch (Exception $e) {
        error_log($e->getMessage());
        $error["database"] = "Something went wrong while applying to event.";
    }
    return $error;

}

function create_timesheet(PDO $pdo)
{

}



/*$searchParams = http_build_query([
       'search_first_name' => $_POST['search_first_name'] ?? '',
       'search_last_name' => $_POST['search_last_name'] ?? '',
       'search_email' => $_POST['search_email'] ?? '',
       'search_birthday' => $_POST['search_birthday'] ?? '',
       'search_local_ass' => $_POST['search_local_ass'] ?? '',
       'search_qualifications' => $_POST['search_qualifications'] ?? ''
   ]);

   header("Location: index.php?page=admin_users&$searchParams");
   exit;*/
?>