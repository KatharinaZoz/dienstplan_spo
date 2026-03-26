<?php include_once __DIR__ . "/../../src/ApplicationRepository.php";
include_once __DIR__ . "/../../src/UserRepository.php";
include_once __DIR__ . "/../../process/admin_dashboard_process.php";

$events = [];
$chosenEvent = [];
$error = [];

$qualifications = ['ersthelfer', 'pflege', 'sanitäter', 'rettungshelfer', 'rettungssanitäter', 'rettungsassistent', 'notfallsanitäter', 'arzt', 'notarzt'];

if (!isset($_SESSION['user_id'])) { //check if user is logged in
    header("Location: index.php?page=login");
    exit;
} elseif ((int) $_SESSION['is_admin'] === 0) { //check if logged-in user is admin
    header("Location: index.php?page=dashboard");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST["create_event"])) {
        $error = [];
        $success = create_event($pdo);
        if (!$success) {
            $error['create_event'] = 'Error during data update.';
            echo "error on event creation";
        } else {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        }
    }
    if (isset($_POST['action'])) {

        if ($_POST['action'] === 'event-edit-button') {
            //get additional data

            $event_id = $_POST["event_id"];
            $participant_data = get_participant_data($pdo, $event_id);
            $participant_data = $participant_data === false ? [] : $participant_data;
            $event_data = get_event_by_id($pdo, $event_id);
            //fill popup with the data
            $show_edit_event_popup = true;
            //show popup
        } else if ($_POST["action"] === "event-apply") {
            $result = apply_to_event($pdo);
            $error = array_merge($result, $error);
            if (!$success) {
                $error['apply_to_event'] = 'Error during application proccess.';
            } else {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
        } else if ($_POST["action"] === "close") {
            $show_edit_event_popup = false;
        } else if ($_POST["action"] === "delete_event") {
            //TODO error handling
            $event_id = $_POST["event_id"];
            $success_delete = delete_event_applications($pdo, (int) $event_id);
            $success_delete &= delete_event($pdo, (int) $event_id);
        } else if ($_POST["action"] === "save_event_changes") {
            $success = update_event_data($pdo);
            if (!$success) {
                echo "error on save";
            }
        }
    }
}
if (empty($events)) {
    $events = get_events($pdo);
}
if (empty($chosenEvent)) {
    // $chosenEvent = get_event_by_id($pdo, 1);
}
?>
<div>
    <div class="event-wrapper">
        <?php
        include __DIR__ . '/event_list.php';
        include __DIR__ . '/event_apply.php';
        ?>
    </div>
    <?php include __DIR__ . '/dashboard_admin/event/app_create.php' ?>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            //----------------CREATE EVENT------------------
            const createEventPopup = document.getElementById("create-event-wrapper");
            const openButton = document.getElementById("create-event-banner"); // a button that opens it
            //const closeButton = createEventPopup.querySelector(".close-popup");
            openButton.addEventListener("click", () => {
                createEventPopup.classList.add("show");
            });
            createEventPopup.addEventListener("click", e => {
                if (e.target === createEventPopup) createEventPopup.classList.remove("show");
            });

            //----------------OPEN EVENT---------------------
            const eventOverview = document.getElementById("results-form-apply");

            //Load event data after click on overview
            document.querySelectorAll(".show-event").forEach(div => {
                div.addEventListener("click", (e) => {

                    const event = JSON.parse(div.dataset.event);
                    const applyButton = document.getElementById("event_apply_button");
                    const canApply = event.user_joined == '0' && event.helpers < event.helper_num ? true : false;

                    if (canApply) {
                        applyButton.classList.remove('disabled')
                        applyButton.classList.add('active');
                        applyButton.disabled = false;
                    } else {
                        applyButton.classList.remove('active')
                        applyButton.classList.add('disabled');  // adds CSS class for styling
                        applyButton.disabled = true;
                    }
                    document.getElementById("event_id_apply").value = event.id;
                    document.getElementById("event_time_start_apply").value = event.time_start;
                    document.getElementById("event_time_end_apply").value = event.time_end;

                    eventOverview.querySelectorAll("span[data-field]").forEach(span => {
                        const field = span.getAttribute("data-field");
                        if (event[field] !== undefined) {
                            if (field === 'helper_num') {
                                span.textContent = event['helpers'] + " / " + event[field];
                            } else if (field === 'qualification') {
                                span.textContent = event[field][0].toUpperCase() + event[field].slice(1);
                            } else {
                                span.textContent = event[field];
                            }

                        }
                    });



                    eventOverview.classList.add("show");
                });
            });
        });
    </script>
    <?php include __DIR__ . '/dashboard_admin/event/event_edit.php'; ?>
</div>