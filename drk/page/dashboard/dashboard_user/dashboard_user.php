<?php include_once __DIR__ . "/../../../src/ApplicationRepository.php";
include_once __DIR__ . "/../../../process/admin_dashboard_process.php";

//include the app overview
$events = [];
$error = [];

$qualifications = ['ersthelfer', 'pflege', 'sanitäter', 'rettungshelfer', 'rettungssanitäter', 'rettungsassistent', 'notfallsanitäter', 'arzt', 'notarzt'];

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['action']) && $_POST["action"] === "event-apply") {
        $result = apply_to_event($pdo);
        $error = array_merge($result, $error);
        if (!$success) {
            $error['apply_to_event'] = 'Error during application proccess.';
        } else {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
}

if (empty($events)) {
    $events = get_events($pdo);
}
?>
<div>
    <div class="event-wrapper">
        <?php
        include __DIR__ . '/../event_list.php';
        include __DIR__ . '/../event_apply.php';
        ?>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
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
                            } else if (field === 'qualifications') {
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
</div>