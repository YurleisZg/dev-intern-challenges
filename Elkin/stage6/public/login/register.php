<?php
require_once '../../includes/auth.php';
include '../../includes/templates.php';
require_once __DIR__ . '/../../config/path.php';
$css_path = AUTH_CSS;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = registerUser($_POST['username'], $_POST['email'], $_POST['password']);

    if ($result['success']) {
        header('Location: login.php');
        exit;
    } else {
        $error = $result['message'];
    }
}
?>

<?= template_header('Register', $css_path) ?>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<div class="auth-container">
    <h2>Registro</h2>

    <form action="" method="post">

        <div class="auth-field">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="auth-field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="auth-field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">Register</button>
    </form>

    <div class="auth-footer">
        ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
    </div>
</div>

