<?php
require_once '../../controllers/auth.php';
include '../templates.php';
require_once __DIR__ . '/../../config/path.php';
$css_path = AUTH_CSS;

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $result = loginUser($_POST['email'], $_POST['password']);

    if ($result['success']) {
        header('Location: ../challenge1.php');
        exit;
    } else {
        $error = $result['message'];
    }
}
?>
<?=template_header('Login', $css_path)?>
<?php if (!empty($error)): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<div class="auth-container">
    <h2>Login</h2>

    <form method="POST">

        <div class="auth-field">
            <label>Email</label>
            <input type="email" name="email" required />
        </div>

        <div class="auth-field">
            <label>Password</label>
            <input type="password" name="password" required />
        </div>

        <button type="submit">Log in</button>
    </form>

    <div class="auth-footer">
        ¿Don't  have an account? <a href="register.php">Sign Up</a>
    </div>
</div>

<?=template_footer()?>
