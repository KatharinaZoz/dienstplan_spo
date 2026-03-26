<div class="event-result-wrapper">

    <form method="post" action="" class="results-form-apply" id="results-form-apply">
        <input type="hidden" id="event_id_apply" name="event_id">
        <input type="hidden" id="event_time_start_apply" name="event_time_start">
        <input type="hidden" id="event_time_end_apply" name="event_time_end">
        <div class="event-edit-button-wrapper">
            <?php if (isset($_SESSION['is_admin']) && (int) $_SESSION['is_admin'] === 1): ?>
                <button type="submit" name="action" value="event-edit-button" id="event-edit-button"
                    class="event-edit-button show"><?php echo $langArray['admin_events']['edit_event_btn']; ?></button>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <!-- Add label for accessibility? -->
            <p class="form-element"> <span data-field="title"></span></p>
        </div>
        <div class="form-group">
            <!-- Add label for accessibility? -->
            <p class="form-element"><?php echo $langArray['admin_events']['start_time']; ?><span
                    data-field="time_start"></span></p>
        </div>
        <div class="form-group">
            <!-- Add label for accessibility? -->
            <p class="form-element"><?php echo $langArray['admin_events']['end_time']; ?><span
                    data-field="time_end"></span></p>
        </div>
        <div class="form-group">
            <!-- Add label for accessibility? -->
            <p class="frm-element"><?php echo $langArray['admin_events']['min_qual']; ?><span
                    data-field="qualification"></span></p>
        </div>
        <div class="form-group">
            <!-- Add label for accessibility? -->
            <p class="form-element"><?php echo $langArray['admin_events']['helper_num']; ?><span
                    data-field="helper_num"></span></p>
        </div>
        <div class="form-group">
            <!-- Add label for accessibility? -->
            <p class="form-element"><?php echo $langArray['admin_events']['app_description']; ?><span
                    data-field="app_description"></span></p>
        </div>
        <div class="search-divider">
            <button class="search-button" type="submit" id="event_apply_button" name="action" value="event-apply"
                class="search-button"><?php echo $langArray['admin_events']['apply_btn']; ?></button>
        </div>
    </form>
</div>