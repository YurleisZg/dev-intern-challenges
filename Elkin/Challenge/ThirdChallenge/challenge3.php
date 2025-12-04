<?php

session_start();

if (isset($_GET['reset'])) {
    session_unset();
    session_destroy();
    session_start();
}

$isGameOver = isset($_GET['gameover']);
$gameOverReason = $_GET['reason'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Challenge 3 - Pattern Memory</title>
    <link rel="stylesheet" href="pattern_game.css">
</head>
<body>
<header>
    <h1>Challenge 3 · Pattern Memory</h1>
    <a href="../index.php">Home</a>
</header>
<div class="game-container">
    <h1 class="title">Pattern Memory</h1>

    <?php if (!$isGameOver): ?>
        <div class="card">
            <p class="description">
                First you will design a secret 5-row pattern.<br>
                Then you must remember it and reproduce it one row at a time. <br><br>
                <strong>You have 3 strikes.</strong>
            </p>
            <a class="btn btn-primary" href="pattern_setup.php">Start game</a>
        </div>
    <?php endif; ?>

    <?php if ($isGameOver): ?>
        <div class="card card-error">
            <h2 class="game-over">Game over</h2>
            <?php if ($gameOverReason === 'time1'): ?>
                <p>Time ran out while you were setting up the pattern.</p>
            <?php elseif ($gameOverReason === 'pattern1'): ?>
                <p>Each row must have at least one active toggle. Try again.</p>
            <?php elseif ($gameOverReason === 'time2'): ?>
                <p>Time ran out during one of the challenge levels.</p>
            <?php elseif ($gameOverReason === 'pattern2'): ?>
                <p>The attempt did not match the expected pattern.</p>
            <?php elseif ($gameOverReason === 'strikes'): ?>
                <p>You reached 3 strikes. Give it another try!</p>
            <?php endif; ?>
            <a class="btn btn-primary" href="challenge3.php?reset=1">Play again</a><br>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
