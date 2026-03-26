<?php declare(strict_types=1);


function get_user_by_email(PDO $pdo, string $email): ?array
{
    try {
        $u_email = trim($email);
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $u_email]);
        $user = $stmt->fetch();
        if (!$user) {
            return [];
        } else {
            return $user;
        }

    } catch (PDOException $e) {
        throw new PDOException($e->getMessage());
    }

}



function get_user_by_id(PDO $pdo)
{
    $id = (int) $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
    try {
        $stmt->execute([':id' => $id]);
    } catch (PDOException $e) {
        error_log($e->getMessage()); // logs to server
        echo "User selection failed. Please try again!"; // user-friendly message
    }

    $user = $stmt->fetch();
    return $user ?: null;
}

function create_user(PDO $pdo): ?array
{
    $first_name = trim($_POST['first_name']);
    $first_name = htmlspecialchars($first_name, ENT_QUOTES, 'UTF-8');
    $last_name = trim($_POST['last_name']);
    $last_name = htmlspecialchars($last_name, ENT_QUOTES, 'UTF-8');
    $email = trim($_POST['email']);
    $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $birthday = trim($_POST['birthday']);
    $birthday = htmlspecialchars($birthday, ENT_QUOTES, 'UTF-8');
    $local_ass = trim($_POST['local_ass']);
    $local_ass = htmlspecialchars($local_ass, ENT_QUOTES, 'UTF-8');
    $passwd_hash = password_hash($_POST['password_create'], PASSWORD_DEFAULT);
    $qualification = trim($_POST['qualifications']);
    $qualification = htmlspecialchars($qualification, ENT_QUOTES, 'UTF-8');
    $is_admin = 0;

    try {
        $stmt = $pdo->prepare(
            "INSERT INTO users (
            first_name, last_name, email, passwd_hash, birthday,
            local_ass, qualification, is_admin)
            VALUES (
            :first_name, :last_name, :email, :passwd_hash, :birthday,
            :local_ass, :qualification, :is_admin
            )"
        );
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo "User creation failed. Please try again.";
    }


    try {
        $stmt->execute([
            ":first_name" => $first_name,
            ":last_name" => $last_name,
            ":email" => $email,
            ":passwd_hash" => $passwd_hash,
            ":birthday" => $birthday,
            ":local_ass" => $local_ass,
            ":qualification" => $qualification,
            ":is_admin" => $is_admin
        ]);

    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo "User creation failed. Please try again.";
    }
    $new_user = get_user_by_email($pdo, $email);

    return $new_user;

}

function update_user(PDO $pdo): bool
{
    $user_id = htmlspecialchars($_POST["user_id"]);
    $first_name = trim($_POST['first_name']);
    $first_name = htmlspecialchars($first_name, ENT_QUOTES, 'UTF-8');
    $last_name = trim($_POST['last_name']);
    $last_name = htmlspecialchars($last_name, ENT_QUOTES, 'UTF-8');
    $email = trim($_POST['email']);
    $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $birthday = trim($_POST['birthday']);
    $birthday = htmlspecialchars($birthday, ENT_QUOTES, 'UTF-8');
    $local_ass = trim($_POST['local_ass']);
    $local_ass = htmlspecialchars($local_ass, ENT_QUOTES, 'UTF-8');
    $qualification = trim($_POST['qualification']);
    $qualification = htmlspecialchars($qualification, ENT_QUOTES, 'UTF-8');
    $is_admin = isset($_POST['is_admin_change']) ? 1 : 0;

    try {
        $stmt = $pdo->prepare(
            "UPDATE users 
            SET 
            first_name = :first_name,
            last_name = :last_name,
            email = :email,
            birthday = :birthday,
            local_ass = :local_ass,
            qualification = :qualification,
            is_admin = :is_admin
            WHERE id = :user_id"
        );
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage());
    }


    try {
        $stmt->execute([
            ":first_name" => $first_name,
            ":last_name" => $last_name,
            ":email" => $email,
            ":birthday" => $birthday,
            ":local_ass" => $local_ass,
            ":qualification" => $qualification,
            ":is_admin" => $is_admin,
            "user_id" => $user_id
        ]);

    } catch (PDOException $e) {
        throw new PDOException($e->getMessage());
    }
    return true;
}

function get_users_by_attribute(PDO $pdo): array
{
    if (!isset($_SESSION['is_admin']) || (int) $_SESSION['is_admin'] === 0) {
        exit('Unauthorized');
    }

    $attributes = $_GET;

    //unset all the added hidden input values from button routing
    unset($attributes['user_search']);
    unset($attributes['action']);
    unset($attributes['page']);

    $whereClauses = [];
    $params = [];

    foreach ($attributes as $column => $value) {
        if ($value === "---SELECT---") {
            continue;
        }

        if (!empty($value)) {
            $whereClauses[] = "$column LIKE :$column";
            $params[":$column"] = '%' . $value . '%';
        }
    }



    $sql = "SELECT id, first_name, last_name, email, birthday, local_ass, qualification, is_admin FROM users";
    if (!empty($whereClauses)) {
        $sql .= " WHERE " . implode(" AND ", $whereClauses);
    }

    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute($params);
        $results = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo "Something went wrong while searching users";
    }
    if (empty($results)) {
    }
    return $results;
}

function delete_user(PDO $pdo, int $user_id): bool
{
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([$user_id]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
    return true;
}

function set_user_session($user)
{
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['is_admin'] = (int) $user['is_admin'];
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
}

function logout_user()
{
    session_unset();
    session_destroy();
}
?>