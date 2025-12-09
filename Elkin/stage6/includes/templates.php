<?php
require_once __DIR__ . '/../config/path.php';
function template_header($title,$css_path) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $base = BASE_URL;
    $isAuth = isset($_SESSION['isAuth']) && $_SESSION['isAuth'] === true;
    echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>$title</title>
		<link rel="stylesheet" href="$base/$css_path">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
    <nav class="navtop">
    	<div>
    		<h1>PHP To Do</h1>
            <a href="$base/public/index.php"><i class="fas fa-home"></i>Home</a>
EOT;
    if ($isAuth) {
        echo <<<EOT
            <a href="{$base}public/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
EOT;
    }
    echo <<<EOT
    	</div>
    </nav>
EOT;
}
function template_footer() {
    echo <<<EOT
    </body>
</html>
EOT;
}
