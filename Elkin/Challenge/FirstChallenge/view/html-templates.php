<?php
require_once __DIR__ . '/../config/path.php';
function template_header($title,$css_path, $css_path1='') {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $base = BASE_URL;
    $challengeUrl = CHALLENGE_URL;
    $isAuth = isset($_SESSION['isAuth']) && $_SESSION['isAuth'] === true;
    
    echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>$title</title>
		<link rel="stylesheet" href="$base/$css_path">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link rel="stylesheet" href="$base/$css_path1">
	</head>
	<body>
    <nav class="navtop">
    	<div>
    		<h1>Challenge 1 - Salary Calculator</h1>
    		<a href="$challengeUrl"><i class="fas fa-graduation-cap"></i>Dashboard</a>
EOT;
    if ($isAuth) {
        echo <<<EOT
            <a href="{$base}view/login/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
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
