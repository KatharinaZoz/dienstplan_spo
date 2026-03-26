<?php
$options = ['ersthelfer', 'pflege', 'sanitäter', 'rettungshelfer', 'rettungssanitäter', 'rettungsassistent', 'notfallsanitäter', 'arzt', 'notarzt'];
$selected = $_GET['qualifications'] ?? '';
?>

<div class="search-wrapper">
    <p class="card-title"><?php echo $langArray['dashboard_user']['search_title']; ?></p>
    <form method="get" action="" class="user-search">
        <div class="search-input-wrapper">
            <div class="form-group">
                <label for="first_name">
                    <?php echo $langArray['auth']['first_name']; ?>
                </label>
                <input id="first_name" type="text" name="first_name" autocomplete="first name"
                    placeholder="<?php echo $langArray['auth']['first_name']; ?>"
                    value="<?php htmlspecialchars($_GET['first_name'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="last_name">
                    <?php echo $langArray['auth']['last_name']; ?>
                </label>
                <input id="last_name" type="text" name="last_name" autocomplete="last name"
                    placeholder="<?php echo $langArray['auth']['last_name']; ?>"
                    value="<?php htmlspecialchars($_GET['last_name'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="email">
                    <?php echo $langArray['auth']['email']; ?>
                </label>
                <input id="email" type="email" name="email" autocomplete="email"
                    placeholder="<?php echo $langArray['auth']['email']; ?>"
                    value="<?php echo htmlspecialchars($_GET['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="birthday">
                    <?php echo $langArray['auth']['birthday']; ?>
                </label>
                <input id="birthday" type="date" name="birthday" autocomplete="birthday"
                    value="<?php echo htmlspecialchars($_GET['birthday'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="local_ass">
                    <?php echo $langArray['auth']['local_association']; ?>
                </label>
                <input id="local_ass" type="text" name="local_ass"
                    placeholder="<?php echo $langArray['auth']['local_association']; ?>"
                    value="<?php echo htmlspecialchars($_GET['local_ass'] ?? '') ?>">
            </div>
            <div class=" form-group">
                <label for="qualifications">
                    <?php echo $langArray['auth']['choose_quali_search']; ?>
                </label>
                <select name="qualification" id="qualifications">
                    <option value="">-- Select --</option>
                    <?php
                    $selected = $_GET['qualification'];
                    foreach ($options as $opt): ?>
                        <option value="<?= $opt ?>" <?= $opt === $selected ? 'selected' : '' ?>>
                            <?= ucfirst($opt) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>


            <input type="hidden" name="page" value="admin_users">
            <input type="hidden" name="user_search" value="1">


        </div>
        <div class="search-divider">
            <button type="submit" name="action" value="user_search"
                class="search-button"><?php echo $langArray['dashboard_user']['search_btn']; ?></button>
        </div>
    </form>
</div>