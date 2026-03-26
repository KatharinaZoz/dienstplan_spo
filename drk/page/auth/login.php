<?php require_once __DIR__ . '/../../process/login_process.php';
// login.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //TODO rewrite with try catch
    if (login_user($pdo)) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        if ((int) $_SESSION['is_admin'] === 0) {
            header("Location: index.php?page=dashboard");
            exit;
        } else if ((int) $_SESSION['is_admin'] === 1) {
            header("Location: index.php?page=admin_events");
            exit;
        }
    } else {
        $error = "Invalid email or password";
    }
}
?>
<div class="card-center">
    <form action="index.php?page=login" method="post" class="card">

        <div class="title-wrapper">
            <h2 class="card-title">
                <?php echo $langArray['auth']['login']; ?>

            </h2>
            <p class="form-context">
                <?php echo $langArray['auth']['disclaimer']; ?>
            </p>
        </div>

        <div class="form-group">
            <label for="email">
                <?php echo $langArray['auth']['email']; ?>
            </label>
            <input id="email" type="text" name="email" autocomplete="email"
                placeholder="<?php echo $langArray['auth']['email']; ?>" required>
        </div>

        <div class=" form-group">
            <label for="password">
                <?php echo $langArray['auth']['password']; ?>
            </label>
            <input id="password" type="password" name="password" autocomplete="current-password"
                placeholder="<?php echo $langArray['auth']['password']; ?>" required>
        </div>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">


        <div class="form-actions">
            <div class="prim-button-wrapper">
                <button type="submit" class="search-button">
                    <?php echo $langArray['auth']['login']; ?>
                </button>
            </div>

            <div class="switch-auth">
                <?php echo $langArray['auth']['not_registered']; ?>
                <a href="index.php?page=register">
                    <?php echo $langArray['auth']['register']; ?>
                </a>
            </div>
        </div>
    </form>
</div>
</div>