<?php
/*
 * Language preference is stored in the session
 * to persist across HTTP requests (stateless protocol).
 * Defaults to German if no preference is set.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Use the session language, default to German
$language = $_SESSION['langID'] ?? 'de';

// Load language file
$langArray = require 'locale/' . $language . '.php';
?>

<!--
Preserve relevant GET parameters (e.g. 'page') during language switch
to maintain routing state and avoid unintended navigation resets.
-->
<form class="language-switch-wrapper" method="get">

    <?php foreach ($_GET as $key => $value): ?>
        <?php if ($key === 'page'): ?>
            <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
        <?php endif; ?>
    <?php endforeach; ?>
    <label for="langID" class="sr-only">
        <?php $langArray['language']['language']; ?>
    </label>

    <select class="language-switch" name="langID" onchange="this.form.submit()">
        <option value="">DE | EN</option>
        <option value="de" <?= ($_GET['langID'] ?? '') === 'de' ? 'selected' : '' ?>>
            <?= $langArray['language']['german']; ?>
        </option>
        <option value="en" <?= ($_GET['langID'] ?? '') === 'en' ? 'selected' : '' ?>>
            <?= $langArray['language']['english']; ?>
        </option>
    </select>

</form>