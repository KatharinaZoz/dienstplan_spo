<?php require_once __DIR__ . '/../../process/login_process.php';

use function PHPUnit\Framework\isEmpty;
require_once __DIR__ . '/../../process/register_process.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    if ($_POST['password_repeat'] !== $_POST['password_create']) {
        $errors['password'] = 'Passwords do not match';
    } else if (strlen($_POST['password_create']) < 8) {
        $errors['password'] = 'Password must be at least 8 characters long';
    }

    if (empty($errors)) {
        try {
            $success = register_user($pdo);
        } catch (PDOException $e) {
            $errors['databank'] = $e->getMessage();
            header("Location: index.php?page=register");
            exit;
        }

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        if ($success) {
            header("Location: index.php?page=dashboard");
            exit;
        } else {
            $errors['login error'] = "Invalid email or password";
            exit;
        }
    }
}
?>

<div class="card-center">
    <form class="card" action="index.php?page=register" method="post">

        <div class="title-wrapper">
            <h2 class="card-title"><?php echo $langArray['auth']['create_account']; ?></h2>
            <p class="form-context"><?php echo $langArray['auth']['disclaimer']; ?></p>
            <?php if (!empty($errors['databank'])): ?>
                <p class="error-context"><?= htmlspecialchars($errors['Databank']) ?></p>
            <?php endif ?>

        </div>

        <div class="form-group">
            <label for="first_name"><?php echo $langArray['auth']['first_name']; ?></label>
            <input id="first_name" type="text" value="asdf" name="first_name" autocomplete="first name"
                placeholder="<?php echo $langArray['auth']['first_name']; ?>" required>
        </div>

        <div class="form-group">
            <label for="last_name"><?php echo $langArray['auth']['last_name']; ?></label>
            <input id="last_name" type="text" value="asdf" name="last_name" autocomplete="last name"
                placeholder="<?php echo $langArray['auth']['last_name']; ?>" required>
        </div>

        <div class="form-group">
            <label for="email"><?php echo $langArray['auth']['email']; ?></label>
            <input id="email" type="email" value="asdf@asdffg.de" name="email" autocomplete="email"
                placeholder="<?php echo $langArray['auth']['email']; ?>" required>
        </div>

        <div class="form-group">
            <label for="birthday"><?php echo $langArray['auth']['birthday']; ?></label>
            <input id="birthday" type="date" value="2001-01-01" name="birthday" autocomplete="birthday" required>
        </div>

        <div class="form-group">
            <label for="local_ass"><?php echo $langArray['auth']['local_association']; ?></label>
            <input id="local_ass" type="text" value="asdf" name="local_ass"
                placeholder="<?php echo $langArray['auth']['local_association']; ?>" required>
        </div>

        <div class="form-group">
            <label for="password_create"><?php echo $langArray['auth']['password_create']; ?></label>
            <input id="password_create" value="asdfasdf" type="password" name="password_create"
                placeholder="<?php echo $langArray['auth']['password']; ?>" required>
            <?php if (!empty($errors['password'])): ?>
                <p class="error-context"><?= htmlspecialchars($errors['password']) ?></p>
            <?php endif ?>

        </div>

        <div class="form-group">
            <label for="password_repeat"><?php echo $langArray['auth']['password_repeat']; ?></label>
            <input id="password_repeat" value="asdfasdf" type="password" name="password_repeat"
                placeholder="<?php echo $langArray['auth']['password']; ?>" required>
        </div>

        <div class=" form-group">
            <label for="qualifications"><?php echo $langArray['auth']['choose_quali']; ?></label>
            <select id="qualifications" name="qualifications">
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
        <input aria-hidden="true" type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">


        <div class="form-actions">
            <div class="prim-button-wrapper">
                <button type="submit" class="search-button"><?php echo $langArray['auth']['register']; ?></button>
            </div>

            <div class="switch-auth">
                <?php echo $langArray['auth']['already_registered']; ?>
                <a href="index.php?page=login">
                    <?php echo $langArray['auth']['login']; ?>
                </a>
            </div>
        </div>
    </form>
</div>