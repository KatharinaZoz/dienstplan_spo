<?php
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['logout'])) {
    header("Location: index.php?page=logout");
    exit;
}
?>

<div class="top-bar">
    <img class="drk-logo" src="assets/kompakt_logo_drk.png" alt="drk logo">
    <?php
    if ($page !== "login" && $page !== "register") {
        ?>
        <div class="header-actions">
            <form method="post" action="">
                <button class="logout-chip" name="logout" type="submit" aria-label="Log out">
                    <span class="logout-label">Ausloggen</span>
                </button>
            </form>
        </div>
        <?php
    }
    include __DIR__ . '/language_switcher.php'; ?>

</div>