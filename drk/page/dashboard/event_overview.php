<?php
include_once __DIR__ . "/../../src/ApplicationRepository.php";


if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['event_mode_switch'])) {
    if ($_GET['event_mode_switch'] === 'show_all' && $currentMode !== 'show_all') {
        $events = get_events($pdo);
    } else if ($_GET['event_mode_switch'] === 'show_applied' && $currentMode !== 'show_applied') {
        $user_events = [];
        foreach ($events as $event) {
            if ($event['user_joined'] == '1') {
                $user_events[] = $event;
            }
        }
        $events = $user_events;
    }
}
$currentMode = $_GET['event_mode_switch'] ?? 'show_all';
?>
<script>
    function changeEventListMode() {
        document.getElementById('event_mode_switch').submit();
    }
</script>
<form method="get" action="" id="event_mode_switch">
    <?php foreach ($_GET as $key => $value): ?>
        <?php if ($key === 'page'): ?>
            <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
        <?php endif; ?>
    <?php endforeach; ?>
    <select class="event_mode_select" name="event_mode_switch" onChange="changeEventListMode();">
        <option value='show_all' <?= $currentMode === 'show_all' ? 'selected' : '' ?>>
            <?php echo $langArray['admin_events']['show_all']; ?></option>
        <option value='show_applied' <?= $currentMode === 'show_applied' ? 'selected' : '' ?>>
            <?php echo $langArray['admin_events']['show_applied']; ?></option>
    </select>
</form>