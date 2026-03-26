<div id="edit_event_popup" class="edit-event-popup <?= !empty($show_edit_event_popup) ? 'show' : '' ?>">
    <form method="post" action="" id="edit_event" class="edit-event">

        <button type="submit" name="action" value="close" class="close-btn">x</button>
        <div class="data-wrapper-event">
            <!-- Event data editing -->
            <div class="edit-event-general-data">
                <input type="hidden" name="event_id" value="<?= htmlspecialchars($event_data['id'] ?? '') ?>">
                <p class="edit-card-title"><?php echo $langArray['admin_events']['edit_event']; ?></p>
                <div class="form-group">
                    <label for="title_change">
                        <?php echo $langArray['admin_events']['event_title']; ?>
                    </label>
                    <input id="title_change" type="text" name="title_change"
                        value="<?= htmlspecialchars($event_data['title'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="time_start_change_event">
                        <?php echo $langArray['admin_events']['start_time']; ?>
                    </label>
                    <input id="time_start_change_event" type="datetime-local" name="time_start_change_event"
                        value="<?= htmlspecialchars($event_data['time_start'] ?? '') ?>" required>
                </div>
                <div class=" form-group">
                    <label for="time_end_change_event">
                        <?php echo $langArray['admin_events']['end_time']; ?>
                    </label>
                    <input id="time_end_change_event" type="datetime-local" name="time_end_change_event"
                        value="<?= htmlspecialchars($event_data['time_end'] ?? '') ?>" required>
                </div>
                <div class=" form-group">
                    <label for="min_qualification_change">
                        <?php echo $langArray['admin_events']['min_qual']; ?>
                    </label>
                    <select id="min_qualification_change" name="min_qualification_change" required>
                        <option value="">-- Select --</option>
                        <option value="ersthelfer" <?= (isset($event_data['qualification']) && $event_data['qualification'] === 'ersthelfer') ? 'selected' : '' ?>>Ersthelfer</option>
                        <option value="pflege" <?= (isset($event_data['qualification']) && $event_data['qualification'] === 'pflege') ? 'selected' : '' ?>>Pflege</option>
                        <option value="sanitäter" <?= (isset($event_data['qualification']) && $event_data['qualification'] === 'sanitäter') ? 'selected' : '' ?>>Sanitäter</option>
                        <option value="rettungshelfer" <?= (isset($event_data['qualification']) && $event_data['qualification'] === 'rettungshelfer') ? 'selected' : '' ?>>Rettungshelfer
                        </option>
                        <option value="rettungssanitäter" <?= (isset($event_data['qualification']) && $event_data['qualification'] === 'rettungssanitäter') ? 'selected' : '' ?>>Rettungssanitäter
                        </option>
                        <option value="rettungsassistent" <?= (isset($event_data['qualification']) && $event_data['qualification'] === 'rettungsassistent') ? 'selected' : '' ?>>Rettungsassistent
                        </option>
                        <option value="notfallsanitäter" <?= (isset($event_data['qualification']) && $event_data['qualification'] === 'notfallsanitäter') ? 'selected' : '' ?>>Notfallsanitäter
                        </option>
                        <option value="arzt" <?= (isset($event_data['qualification']) && $event_data['qualification'] === 'arzt') ? 'selected' : '' ?>>Arzt</option>
                        <option value="notarzt" <?= (isset($event_data['qualification']) && $event_data['qualification'] === 'notarzt') ? 'selected' : '' ?>>Notarzt</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><?php echo $langArray['admin_events']['helper_num']; ?></label>
                    <input id="helper_num_change" type="number" name="helper_num_change"
                        value="<?= htmlspecialchars($event_data['helper_num'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label
                        for="app_description_change"><?php echo $langArray['admin_events']['app_description']; ?></label>
                    <textarea id="app_description_change" name="app_description_change"
                        rows="5"><?= htmlspecialchars($event_data['app_description'] ?? '') ?></textarea>
                </div>
            </div>
            <!-- Participant data editing -->
            <div class="edit-event-participant-data">
                <p class="edit-card-title"><?php echo $langArray['admin_events']['helper']; ?></p>
                <?php if (!empty($participant_data)): ?>
                    <?php foreach ($participant_data as $user): ?>

                        <div class="participant-form">
                            <input type="hidden" name="user_id[]" value="<?= $user['id'] ?>">

                            <div class="user_data_non_edit">
                                <p><?= htmlspecialchars($user['first_name'] . " " . $user['last_name']) ?></p>
                                <p><?= htmlspecialchars($user['email']) ?></p>
                                <p><?= htmlspecialchars($user['local_ass']) ?></p>
                            </div>
                            <div class="user-data-non-edit2">
                                <select id="approval_status_change" name="approval_status_change[]">
                                    <option value="pending" <?= $user['approval_status'] === 'pending' ? 'selected' : '' ?>>
                                        <?php echo $langArray['admin_events']['pending']; ?>
                                    </option>

                                    <option value="approved" <?= $user['approval_status'] === 'approved' ? 'selected' : '' ?>>
                                        <?php echo $langArray['admin_events']['confirmed']; ?>
                                    </option>

                                    <option value="denied" <?= $user['approval_status'] === 'denied' ? 'selected' : '' ?>>
                                        <?php echo $langArray['admin_events']['denied']; ?>
                                    </option>
                                </select>
                                <div class="time-sheet-wrapper">
                                    <input type="datetime-local" name="time_start_change_user[]"
                                        value="<?= date('Y-m-d\TH:i', strtotime($user['time_start'])) ?>">
                                    <input type="datetime-local" name="time_end_change_user[]"
                                        value="<?= date('Y-m-d\TH:i', strtotime($user['time_end'])) ?>">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="edit-button-wrapper">
            <button type="submit" name="action" value="delete_event"
                class="prim-button"><?php echo $langArray['admin_events']['delete']; ?></button>
            <button type="submit" name="action" value="save_event_changes"
                class="btn-primary"><?php echo $langArray['admin_events']['save']; ?></button>
            <button class="btn-secondary"><?php echo $langArray['admin_events']['export_timesheet']; ?></button>
        </div>
    </form>
</div>