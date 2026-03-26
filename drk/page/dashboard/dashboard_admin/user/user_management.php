<?php require_once __DIR__ . '/../../../../src/UserRepository.php';
require_once __DIR__ . '/../../../../process/admin_dashboard_process.php';

if (!isset($_SESSION['user_id'])) { //check if user is logged in
    header("Location: index.php?page=login");
    exit;
} elseif ((int) $_SESSION['is_admin'] === 0) { //check if logged-in user is admin
    header("Location: index.php?page=dashboard");
    exit;
}

/*$search_first_name = htmlspecialchars($_GET['first_name'] ?? '');
$search_last_name = htmlspecialchars($_GET['last_name'] ?? '');
$search_email = htmlspecialchars($_GET['email'] ?? '');
$search_birthday = htmlspecialchars($_GET['birthday'] ?? '');
$search_local_ass = htmlspecialchars($_GET['local_ass'] ?? '');
$search_qualifications = htmlspecialchars($_GET['qualifications'] ?? '');*/

$results = [];
$status = null;
$error = [];


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'user_search') {
        $results = get_users_by_attribute($pdo);

    }
}
if ($_SERVER['REQUEST_METHOD'] === "POST") {//edit_user
    if (isset($_POST['action'])) { //admin submitting changed user data
        if ($_POST['action'] === 'change_data') {
            $error = [];
            $sucsess = change_user_data($pdo, $_POST);
            if ($sucsess) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                header("Location: index.php?page=admin_users&status=saved");
                exit;
            } else {
                $error['update'] = 'Error during data update.';
            }
        } elseif ($_POST['action'] === 'delete_user') {
            //TODO add secondary confirmation
            $success = delete_user_process($pdo);
        }
    } elseif (isset($_POST['close_no_save'])) {  //closing the edit window with the "x" button
        //prompt for confirmation to not save
        $status = "close_user";
        //close popup without reload of page
    } elseif (isset($_POST['confirm_no_save'])) { //confirming the close without saving action

    } elseif (isset($_POST['confirm_save_changes'])) { //confirming the changes to be saved

    }
}
$hasError = !empty($error);
?>

<div>
    <div class="user-wrapper">
        <!-- Input form for user search -->
        <?php include 'user_search.php'; ?>

        <div class="results-wrapper">
            <?php if (empty($results)):
                ?>
                <p class="placeholder-search-results"><?php echo $langArray['admin_events']['no_results']; ?></p>
            <?php else:
                // Show all user matching search params with general data
                foreach ($results as $result): ?>
                    <div class="show-user" data-user='<?= json_encode($result, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>

                        <div class="event-card-header">
                            <span
                                class="event-title"><?= htmlspecialchars($result["first_name"]) . " " . htmlspecialchars($result["last_name"]) ?></span>
                            <span class="event-qualification"><?= ucfirst($result['qualification']) ?></span>
                        </div>

                        <div class="user-email">
                            <span><?php echo htmlspecialchars($result["email"]) ?></span>
                        </div>

                        <div class="user-local-ass">
                            <span><?php echo htmlspecialchars($result['local_ass']) ?></span>
                        </div>

                    </div>
                <?php endforeach ?>
            <?php endif ?>
            <script>document.addEventListener("DOMContentLoaded", () => {

                    const userPopup = document.getElementById("user-popup");
                    const confirmPopup = document.getElementById("crit_confirm");
                    const form = document.getElementById("edit-user-form");

                    // --- Open edit popup when clicking user ---
                    document.querySelectorAll(".show-user").forEach(div => {
                        console.log(div.dataset.user);
                        div.addEventListener("click", (e) => {

                            const user = JSON.parse(div.dataset.user);

                            document.getElementById("first_name_change").value = user.first_name;
                            document.getElementById("last_name_change").value = user.last_name;
                            document.getElementById("email_change").value = user.email;
                            document.getElementById("birthday_change").value = user.birthday?.slice(0, 10);
                            document.getElementById("local_ass_change").value = user.local_ass;
                            document.getElementById("qualifications_change").value = user.qualification;
                            document.getElementById("is_admin_change").checked = user.is_admin == 1;
                            document.getElementById("user_id").value = user.id;

                            userPopup.classList.add("show");
                        });
                    });

                    // Prevent clicks inside the popup content from closing the popup
                    const popupContent = document.querySelector("#user-popup form"); // or .popup-content wrapper
                    popupContent.addEventListener("click", e => {
                        //e.stopPropagation();
                    });

                    //Close edit popup when clicking outside
                    userPopup.addEventListener("click", (e) => {
                        if (e.target === userPopup) {
                            userPopup.classList.remove("show");
                        }

                    });


                    //Intercept form submit and show confirmation popup
                    /*form.addEventListener("submit", (e) => {
                        e.preventDefault();   // stop page reload

                        confirmPopup.classList.add("show");
                    });*/

                    //Handle PHP status flag (after POST reload case)
                    const status = "<?= $status ?>";

                    if (status === "saved" || status === "closed") {
                        confirmPopup.classList.add("show");
                    }

                });

            </script>
            <!-- Popup to edit user data -->
            <?php include 'user_popup.php'; ?>
            <!-- Popup to confirm user changes to save or not 
            <?php include 'confirm_crit_action.php' ?>-->
        </div>

    </div>
</div>