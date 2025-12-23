<?php
session_start();

// If there is NO user in session, redirect to login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];

if (isset($_GET['theme'])) {
    $requestedTheme = $_GET['theme'];

    if ($requestedTheme === 'dark' || $requestedTheme === 'light') {
        // Save in cookie for 30 days
        setcookie('theme', $requestedTheme, time() + 60*60*24*30, '/');
        $theme = $requestedTheme;
    }
} elseif (isset($_COOKIE['theme'])) {
    // If not coming in URL, use existing cookie
    $theme = $_COOKIE['theme'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home</title>
    <style>
    body.light {
      background: #f5f5f5;
      color: #222;
    }
    body.dark {
      background: #111;
      color: #eee;
    }
    .theme-switcher a {
      margin-right: 8px;
    }
  </style>
</head>
<body class="<?php echo htmlspecialchars($theme); ?>">
  <h1>Welcome, <?php echo $user ?></h1>

  <p>This is a protected page only for logged-in users.</p>

   <div class="theme-switcher">
    <strong>Theme:</strong>
    <a href="?theme=light">Light</a>
    <a href="?theme=dark">Dark</a>
  </div>

</body>
</html>
