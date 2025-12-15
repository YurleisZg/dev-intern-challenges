<?php


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App</title>
   <link rel="stylesheet" href="../../stage7_9/public/css/styles.css">
</head>

<body>

    <div class="app-container">

        <aside class="sidebar">
            <div class="sidebar-header">
                <h1>Welcome! Challenges Updated</h1>
            </div>

            <nav class="sidebar-nav">
                <a href="index.php?action=salary" class="nav-item <?= $activeItem === 'salary' ? 'active' : '' ?>">
                    <span class="icon"></span> Salary Calculator
                </a>
               <a href="index.php?action=pattern_game" class="nav-item <?= $activeItem === 'pattern_game' ? 'active' : '' ?>">
                    <span class="icon"> </span> Pattern Game
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="../../../index.php" class="nav-item">
                    Back to Home
                </a>
                <a href="logout.php" class="nav-item">
                    Log Out
                </a>
            </div>
        </aside>

        <main class="main-content">

            <header class="top-bar">
                <div class="search-box">
                    <span class="icon">🔍</span>
                    <input type="text" placeholder="Search ...">
                </div>

                <div class="user-menu">
                    <span class="user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Guest') ?></span>
                    <span class="user-email"><?= htmlspecialchars($_SESSION['user_email'] ?? 'email@example.com') ?></span>
                </div>
            </header>

            <div class="content-area">
                <?= $content ?>
            </div>
        </main>
    </div>

</body>
</html>