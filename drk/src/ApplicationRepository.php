<?php declare(strict_types=1);

function get_events(PDO $pdo): array
{
    $currentUserId = (int) $_SESSION['user_id'];
    $sql = "SELECT a.*, COUNT(CASE WHEN au.approval_status IN ('approved', 'pending') THEN au.user_id END) AS helpers,
     CASE 
        WHEN EXISTS (
            SELECT 1 FROM appointment_users au2 
            WHERE au2.appointment_id = a.id 
            AND au2.user_id = :user_id  
        ) THEN 1 ELSE 0 
    END AS user_joined
    FROM appointments a
    LEFT JOIN appointment_users au
        ON a.id = au.appointment_id
    GROUP BY a.id
    ORDER BY a.time_start";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([':user_id' => $currentUserId]);
        $events = $stmt->fetchAll();
        if (!$events) {
            return [];
        } else {
            return $events;
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
    return [];
}

function get_user_events(PDO $pdo)
{
    validate_user(false, $pdo);
    $id = $_SESSION['user_id'];
    $sql = "SELECT a.*, COUNT(CASE WHEN au.approval_status IN ('approved', 'pending') THEN au.user_id END) AS helpers
    FROM appointments a 
    LEFT JOIN appointment_users au
        ON a.id = au.appointment_id
    WHERE au.user_id = :id
    ";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $events = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }

    return $events;
}

function get_event_by_id(PDO $pdo, int $id): array
{
    $sql = "SELECT a.*, COUNT(CASE WHEN au.approval_status IN ('approved', 'pending') THEN au.user_id END) AS helpers
    FROM appointments a 
    LEFT JOIN appointment_users au
        ON a.id = au.appointment_id
    WHERE a.id = :id";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([":id" => $id]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
    return $event;
}

function get_participant_data(PDO $pdo, int $id): array|bool
{
    $sql = "SELECT 
        u.id, 
        u.first_name, 
        u.last_name, 
        u.email, 
        u.local_ass, 
        au.approval_status, 
        au.time_start, 
        au.time_end

    FROM appointment_users au

    JOIN users u 
        ON u.id = au.user_id

    WHERE au.appointment_id = :id";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([":id" => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo "Sonething went wrong.";
    }
    return $data;
}

function check_event_by_id(PDO $pdo, string $id): bool
{
    $sql = "SELECT title FROM appointments WHERE id = :event_id";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
    return !empty($event);
}

function get_events_by_attribute(PDO $pdo)
{
    $attributes = $_GET;

    $whereClauses = [];
    $params = [];

    foreach ($attributes as $column => $value) {
        if (!empty($value)) {
            $whereClauses[] = "$column LIKE :$column";
            $params[":$column"] = '%' . $value . '%';
        }
    }
    $sql = "SELECT id, title, qualification, time_start, time_end, helper_num, app_description FROM appointments";
    if (!empty($whereClauses)) {
        $sql .= " WHERE " . implode(" AND ", $whereClauses);
    }
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute($params);
        $results = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return [];
    }
    return $results;
}

function create_event(PDO $pdo)
{
    $title = htmlspecialchars(trim($_POST["event_title"]));
    $start_time = htmlspecialchars(trim(str_replace("T", " ", $_POST["start_time"])));
    $end_time = htmlspecialchars(trim(str_replace("T", " ", $_POST["end_time"])));
    $helper_num = htmlspecialchars(trim($_POST["helper_num"]));
    $qualification = htmlspecialchars(trim($_POST["qualification"]));
    $description = htmlspecialchars(trim($_POST["app_description"]));

    try {
        $stmt = $pdo->prepare("INSERT INTO appointments (
        title, qualification, time_start, time_end, helper_num, app_description)
        VALUES (:title, :qualification, :start_time, :end_time, :helper_num, :app_description)"
        );

        $stmt->execute([
            ":title" => $title,
            ":qualification" => $qualification,
            ":start_time" => $start_time,
            ":end_time" => $end_time,
            ":helper_num" => $helper_num,
            ":app_description" => $description
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo "Event creation failed. Please try again.";
        return false;
    }
    return true;
}

function delete_event(PDO $pdo, int $id)
{
    $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = :id");
    try {
        $stmt->execute([':id' => $id]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
    return true;
}

function delete_event_applications(PDO $pdo, int $id)
{
    $stmt = $pdo->prepare("DELETE FROM appointment_users WHERE appointment_id = :id");
    try {
        $stmt->execute([':id' => $id]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
    return true;
}

/*
 * Updates core event data.
 * Uses prepared statements to prevent SQL injection.
 * Named parameters improve readability and maintainability.
 */
function update_event(PDO $pdo)
{
    /*
     * The appointments table stores core event metadata.
     * Participant assignments are separated into a junction table
     * to avoid redundancy and support relational integrity.
     */
    $id = $_POST['event_id'];
    $title = $_POST['title_change'];
    $qualification = $_POST['min_qualification_change'];
    $time_start = $_POST['time_start_change_event'];
    $time_end = $_POST['time_end_change_event'];
    $helper_num = $_POST['helper_num_change'];
    $app_description = $_POST['app_description_change'];

    $sql = 'UPDATE appointments 
    SET
    title = :title,
    qualification = :qualification,
    time_start = :time_start,
    time_end = :time_end,
    helper_num = :helper_num,
    app_description = :app_description
    WHERE id = :id';
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":title" => $title,
            ":qualification" => $qualification,
            ":time_start" => $time_start,
            ":time_end" => $time_end,
            ":helper_num" => $helper_num,
            ":app_description" => $app_description,
            ":id" => $id
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
    return true;
}

/*
 * appointment_users acts as a junction table (many-to-many relation)
 * between users and appointments.
 *
 * This design allows:
 * - Multiple users per appointment
 * - Per-user metadata (approval_status, individual time adjustments)
 * - Scalable extension without altering core event structure
 */
function update_participants(PDO $pdo)
{
    /*
     * Input validation and sanitization are expected
     * to occur before reaching the persistence layer.
     */
    $id_event = $_POST['event_id'];
    $user_ids = $_POST['user_id'];
    $statuses = $_POST['approval_status_change'];
    $start_times = $_POST['time_start_change_user'];
    $end_times = $_POST['time_end_change_user'];

    $sql = 'UPDATE appointment_users
            SET
                time_start = :time_start,
                time_end = :time_end,
                approval_status = :approval_status
            WHERE appointment_id = :id_event
            AND user_id = :id_user';

    $stmt = $pdo->prepare($sql);

    try {
        /*
         * Prepared statement is reused inside the loop.
         * This avoids repeated parsing/compiling of SQL and improves efficiency.
         */
        foreach ($user_ids as $index => $user_id) {
            $stmt->execute([
                ':id_user' => $user_id,
                ':id_event' => $id_event,
                ':approval_status' => $statuses[$index],
                ':time_start' => $start_times[$index],
                ':time_end' => $end_times[$index]
            ]);
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return false;
    }

    return true;
}

function apply_user(PDO $pdo, int $id)
{
    $user_id = (int) $_SESSION["user_id"];
    $event_id = (int) $_POST["event_id"];
    $event_start_time = htmlspecialchars(trim(str_replace("T", " ", $_POST["event_time_start"])));
    $event_end_time = htmlspecialchars(trim(str_replace("T", " ", $_POST["event_time_end"])));
    $approval_status = 'pending';

    try {
        $stmt = $pdo->prepare("INSERT INTO appointment_users (appointment_id, user_id, time_start, time_end, approval_status)
    VALUES (:event_id, :user_id, :time_start, :time_end, :approval_status)"
        );
        $stmt->execute([
            ":event_id" => $event_id,
            ":user_id" => $user_id,
            ":time_start" => $event_start_time,
            ":time_end" => $event_end_time,
            ":approval_status" => $approval_status
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo "Application failed. Please try again";
        return false;
    }
    return true;

}

?>