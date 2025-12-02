<?php
session_start();

// If there is no stored pattern, go back to the game start
if (!isset($_SESSION['rows'])) {
    header('Location: challenge3.php');
    exit;
}

function registerStrikeAndRedirect(): void
{
    $_SESSION['strikes'] = ($_SESSION['strikes'] ?? 0) + 1;

    if ($_SESSION['strikes'] >= 3) {
        session_unset();
        session_destroy();
        header('Location: challenge3.php?gameover=1&reason=strikes');
        exit;
    }

    // Move one level back if possible
    if (($_SESSION['current_level'] ?? 1) > 1) {
        $_SESSION['current_level']--;
    }

    // Reset timer for the updated level
    unset($_SESSION['pattern_challenge_deadline'], $_SESSION['pattern_challenge_time_limit']);

    header('Location: pattern_play.php');
    exit;
}

$rows = $_SESSION['rows'];
$currentLevel = $_SESSION['current_level'] ?? 1;
$strikes = $_SESSION['strikes'] ?? 0;

// Handle submitted attempt
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check time limit
    if (!isset($_SESSION['pattern_challenge_deadline']) || time() > $_SESSION['pattern_challenge_deadline']) {
        registerStrikeAndRedirect();
    }

    $targetRow = $rows[$currentLevel - 1];
    $guess = $_POST['guess'] ?? [];

    $guessPattern = [];
    for ($i = 0; $i < 5; $i++) {
        $guessPattern[$i] = isset($guess[$i]) ? 1 : 0;
    }

    if ($guessPattern === $targetRow) {
        // Correct match
        if ($currentLevel >= 5) {
            // Game completed
            session_unset();
            session_destroy();
            header('Location: pattern_success.php');
            exit;
        }

        $_SESSION['current_level'] = $currentLevel + 1;
        unset($_SESSION['pattern_challenge_deadline'], $_SESSION['pattern_challenge_time_limit']);
        header('Location: pattern_play.php');
        exit;
    }

    // Incorrect pattern
    registerStrikeAndRedirect();
}

// GET: show the current level
$currentLevel = $_SESSION['current_level'];
$strikes = $_SESSION['strikes'] ?? 0;

// Initialize or refresh timer
if (!isset($_SESSION['pattern_challenge_deadline']) || !isset($_SESSION['pattern_challenge_time_limit'])) {
    $randSeconds = rand(10, 15);
    $_SESSION['pattern_challenge_time_limit'] = $randSeconds;
    $_SESSION['pattern_challenge_deadline'] = time() + $randSeconds;
}

$remainingSeconds = max(0, $_SESSION['pattern_challenge_deadline'] - time());
if ($remainingSeconds <= 0) {
    registerStrikeAndRedirect();
}

$targetRow = $rows[$currentLevel - 1];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Challenge 3 - Level <?php echo $currentLevel; ?></title>
    <link rel="stylesheet" href="pattern_game.css">
    <script>
        let remaining = <?php echo $remainingSeconds; ?>;
        function startTimer() {
            const timerSpan = document.getElementById('timer');
            const form = document.getElementById('guess-form');

            const interval = setInterval(() => {
                remaining--;
                if (remaining <= 0) {
                    clearInterval(interval);
                    timerSpan.textContent = '0';
                    form.submit();
                } else {
                    timerSpan.textContent = remaining;
                }
            }, 1000);
        }
        window.onload = startTimer;
    </script>
</head>
<body>
<header>
    <h1>Challenge 3 · Pattern Memory</h1>
    <a href="../index.php">Home</a>
</header>
<div class="game-container">
    <h1 class="title">Pattern Challenge</h1>

    <div class="hud">
        <span>Level: <strong><?php echo $currentLevel; ?>/5</strong></span>
        <span>Strikes: <strong><?php echo $strikes; ?>/3</strong></span>
        <span>Time: <strong id="timer"><?php echo $remainingSeconds; ?></strong> s</span>
    </div>

    <div class="card">
        <h2>Your attempt</h2>
        <form id="guess-form" method="post">
            <div class="row">
                <span class="row-label">Recreate the row</span>
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <label class="toggle">
                        <input type="checkbox" name="guess[<?php echo $i; ?>]">
                        <span class="toggle-ui"></span>
                    </label>
                <?php endfor; ?>
            </div>

            <button type="submit" class="btn btn-primary mt">Check pattern</button>
        </form>
    </div>

    <div class="footer-links">
        <a href="challenge3.php?reset=1" class="link">Restart game</a>
    </div>
</div>
</body>
</html>


