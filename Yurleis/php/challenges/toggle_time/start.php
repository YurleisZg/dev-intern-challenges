<?php

include '../../../back_button.php';
session_start();

if (isset($_GET['reset'])) {
    session_unset();
    session_destroy();
    session_start();
}

$gameover = isset($_GET['gameover']);
$reason = $_GET['reason'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pattern Game - Start</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="game-container">
    <h1 class="title">Pattern Game</h1>

    <?php if (!$gameover): ?>
     <div class="card">
        <p class="description">
            First you'll create a 5-row pattern.<br>
            Then you'll have to memorize it and repeat it level by level. <br><br>
            <strong>You have 3 strikes.</strong>
        </p>
        <a class="btn btn-primary" href="stage1.php">Start Game</a>
    </div>
    <?php endif; ?>
    

    <?php if ($gameover): ?>
        <div class="card card-error">
            <h2 class="game-over">Game Over</h2>
            <?php if ($reason === 'time1'): ?>
            <p>Time ran out in Stage 1.</p>
            <?php elseif ($reason === 'pattern1'): ?>
            <p>At least one row was left completely off in Stage 1.</p>
            <?php elseif ($reason === 'time2'): ?>
            <p>Time ran out in a Stage 2 level.</p>
            <?php elseif ($reason === 'pattern2'): ?>
            <p>The entered combination did not match the target pattern.</p>
            <?php elseif ($reason === 'strikes'): ?>
            <p>You have accumulated 3 strikes. Try again!</p>
            <?php endif; ?>
            <div>
                
            </div>
            <a class="btn btn-primary" href="start.php?reset=1">Play Again</a><br>
        </div>
        <?php endif; ?>
</div>
</body>
</html>
