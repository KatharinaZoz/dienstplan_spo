<div class="user-popup" id="user-popup">
    <form method="post" action="" class="edit-user">

        <!--<button type="button" class="close-button" name="close_no_save">X</button>-->
        <p class="card-title"><?php echo $langArray['admin_users']['edit_user_title']; ?></p>
        <input type="hidden" id="user_id" name="user_id">
        <div class="form-group">
            <label for="first_name_change">
                <?php echo $langArray['auth']['first_name']; ?>
            </label>
            <input id="first_name_change" type="text" name="first_name" autocomplete="first name"
                placeholder="<?php echo $langArray['auth']['first_name']; ?>" required>
        </div>

        <div class="form-group">
            <label for="last_name_change">
                <?php echo $langArray['auth']['last_name']; ?>
            </label>
            <input id="last_name_change" type="text" name="last_name" autocomplete="last name"
                placeholder="<?php echo $langArray['auth']['last_name']; ?>" required>
        </div>

        <div class="form-group">
            <label for="email_change">
                <?php echo $langArray['auth']['email']; ?>
            </label>
            <input id="email_change" type="email" name="email" autocomplete="email"
                placeholder="<?php echo $langArray['auth']['email']; ?>" required>
        </div>

        <div class="form-group">
            <label for="birthday_change">
                <?php echo $langArray['auth']['birthday']; ?>
            </label>
            <input id="birthday_change" type="date" name="birthday" autocomplete="birthday" required>
        </div>
        <div class="form-group">
            <label for="local_ass_change">
                <?php echo $langArray['auth']['local_association']; ?>
            </label>
            <input id="local_ass_change" type="text" name="local_ass"
                placeholder="<?php echo $langArray['auth']['local_association']; ?>" required>
        </div>
        <div class=" form-group">
            <label for="qualifications_change">
                <?php echo $langArray['auth']['choose_quali_search']; ?>
            </label>
            <select id="qualifications_change" name="qualification" required>
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
        <div class="admin-checkbox-wrapper">
            <input type="checkbox" id="is_admin_change" name="is_admin_change">
            <label for="is_admin_change"><?php echo $langArray['admin_users']['user_is_admin']; ?></label>
        </div>
        <input aria-hidden="true" type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <!--<input type="hidden" name="search_first_name" value=<?php //echo htmlspecialchars($search_first_name) ?>>
        <input type="hidden" name="search_last_name" value="<?php //echo htmlspecialchars($search_last_name) ?>">
        <input type="hidden" name="search_email" value="<?php //echo htmlspecialchars($search_email) ?>">
        <input type="hidden" name="search_birthday" value="<?php //echo htmlspecialchars($search_birthday) ?>">
        <input type="hidden" name="search_local_ass" value="<?php //echo htmlspecialchars($search_local_ass) ?>">
        <input type="hidden" name="search_qualifications"
            value="<?php //echo htmlspecialchars($search_qualifications) ?>">-->
        <div class="search-divider">
            <button class="search-button" name="action" value="change_data"
                type="submit"><?php echo $langArray['admin_events']['save']; ?></button>
        </div>
        <div class="delete-divider">
            <button class="prim-button" name="action" value="delete_user"
                type="submit"><?php echo $langArray['admin_users']['delete_user']; ?></button>
        </div>
    </form>
</div>