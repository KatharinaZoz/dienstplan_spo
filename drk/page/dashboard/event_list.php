<?php
include_once __DIR__ . "/../../src/UserRepository.php"; ?>

<div class="event-search-wrapper">
    <?php if ($_SESSION['is_admin'] == 1): ?>
        <div class="create-event-button-wrapper" id="create-event">
            <button class="create-event-banner" id="create-event-banner">+</button>
            <p class="create-event-text"><?php echo $langArray['admin_events']['create_event_btn']; ?></p>
        </div>
    <?php endif ?>
    <?php if (empty($events)):
        ?>
        <p class="placeholder-search-results"><?php echo $langArray['admin_events']['no_results']; ?></p>
    <?php else:
        // Show all user matching search params with general data
        $user_quali_value = array_search(get_user_by_id($pdo)['qualification'], $qualifications);

        foreach ($events as $event):
            $event_quali_value = array_search($event['qualification'], $qualifications);

            // Determine class dynamically
            $classes = 'event-card';
            if ($user_quali_value >= $event_quali_value) {
                $classes .= ' active';
            } else if ($user_quali_value < $event_quali_value && (int) $_SESSION['is_admin'] === 1) {
                $classes .= '-admin';
            }
            $classes .= ' show-event';
            ?>
            <div class="<?= $classes ?>"
                data-event="<?= htmlspecialchars(json_encode($event, JSON_HEX_APOS | JSON_HEX_QUOT)) ?>">


                <div class="event-card-header">
                    <span class="event-title">
                        <?= htmlspecialchars($event['title']) ?>
                    </span>
                    <span class="event-qualification">
                        <?= ucfirst($event['qualification']) ?>
                    </span>
                </div>

                <div class="event-time">
                    <?= $event['time_start'] ?>
                    –
                    <?= $event['time_end'] ?>
                </div>

                <div class="event-helpers">
                    <?php echo $langArray['admin_events']['helper']; ?>
                    <?= $event['helpers'] ?> /
                    <?= $event['helper_num'] ?>
                </div>
            </div>
        <?php endforeach ?>
    <?php endif ?>
</div>