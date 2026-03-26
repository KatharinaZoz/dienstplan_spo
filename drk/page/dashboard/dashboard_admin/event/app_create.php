<div class="create-event-wrapper" id="create-event-wrapper" name="create-event-wrapper">
    <form method="post" action="" class="create-event">

        <p class="card-title"><?php echo $langArray['admin_events']['create_event']; ?></p>
        <div class="form-group">
            <label for="event_title"><?php echo $langArray['admin_events']['event_title']; ?></label>
            <input id="event_title" type="text" name="event_title"
                placeholder="<?php echo $langArray['admin_events']['event_title_plch']; ?>">
        </div>
        <div class="form-group">
            <label for="start_time"><?php echo $langArray['admin_events']['start_time']; ?></label>
            <input id="start_time" type="datetime-local" name="start_time" placeholder="1970-01-01 10:10">
        </div>
        <div class="form-group">
            <label for="end_time"><?php echo $langArray['admin_events']['end_time']; ?></label>
            <input id="end_time" type="datetime-local" name="end_time" placeholder="1970-01-02 10:10">
        </div>
        <div class="form-group">
            <label for="helper_num"><?php echo $langArray['admin_events']['helper_num']; ?></label>
            <input id="helper_num" type="number" name="helper_num" min="1" placeholder="10">
        </div>
        <div class=" form-group">
            <label for="qualification">
                <?php echo $langArray['auth']['choose_quali_search']; ?>
            </label>
            <select id="qualification" name="qualification" required>
                <option value="">-- Select --</option>
                <option value="ersthelfer">Ersthelfer</option>
                <option value="pflege">Pflege</option>
                <option value="sanitäter">Sanitäter</option>
                <option value="rettungshelfer">Rettungshelfer</option>
                <option value="rettungssanitäter">Rettungssanitäter</option>
                <option value="rettungsassistent">Rettungsassistent</option>
                <option value="notfallsanitäter">Notfallsanitäter</option>
                <option value="arzt">Arzt</option>
                <option value="notarzt">Notarzt</option>
            </select>
        </div>
        <div class="form-group">
            <label for="app_description"><?php echo $langArray['admin_events']['app_description']; ?></label>
            <textarea id="app_description" name="app_description" rows="5"
                placeholder="Describe it like your mom is watching…">
            </textarea>
        </div>
        <div class="create-button-wrapper">
            <button type="submit" name="create_event"
                class="create-event-button"><?php echo $langArray['admin_events']['create_event_btn']; ?></button>
        </div>
    </form>
</div>