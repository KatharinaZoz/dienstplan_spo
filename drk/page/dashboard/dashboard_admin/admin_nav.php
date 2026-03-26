<?php
$currentPage = $_GET['page'] ?? 'admin_events';
?>

<div class="admin-nav-container">
    <form method="get" class="admin-nav-wrapper">

        <button type="submit" class="nav-user <?= $currentPage === 'admin_users' ? 'active' : '' ?>" name="page"
            value="admin_users">
            <?php echo $langArray['admin_nav']['user_management']; ?>
        </button>

        <button type="submit" class="nav-app <?= $currentPage === 'admin_events' ? 'active' : '' ?>" name="page"
            value="admin_events">
            <?php echo $langArray['admin_nav']['event_management']; ?>
        </button>

    </form>
</div>