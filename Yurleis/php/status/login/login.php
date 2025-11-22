<?php
session_start(); // Always before any HTML

// If already logged in, redirect to home
if (isset($_SESSION['user'])) {
    header('Location: home.php');
    exit;
}

$error = '';

// Process form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // For example: hardcoded user and password
    $validUser = 'admin';
    $validPass = '123456';

    if ($username === $validUser && $password === $validPass) {
        // Save user in session
        $_SESSION['user'] = $username;
        header('Location: home.php');
        exit;
    } else {
        $error = 'Incorrect username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en"></html>
<head>
  <meta charset="UTF-8">
  <title>Login</title>
</head>
<body>
  <h1>Login</h1>

  <?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>

  <form method="post" action="">
    <label>
      Username:
      <input type="text" name="username" required>
    </label>
    <br><br>

    <label>
      Password:
      <input type="password" name="password" required>
    </label>
    <br><br>

    <button type="submit">Login</button>
  </form>
</body>
</html>
