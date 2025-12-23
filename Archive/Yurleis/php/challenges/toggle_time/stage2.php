<?php
session_start();

// If there's no pattern, return to start
if (!isset($_SESSION['rows'])) {
    header('Location: start.php');
    exit;
}

function handleStrike() {
    $_SESSION['strikes'] = ($_SESSION['strikes'] ?? 0) + 1;

    if ($_SESSION['strikes'] >= 3) {
        session_unset();
        session_destroy();
        header('Location: start.php?gameover=1&reason=strikes');
        exit;
    }

    // Go back a level if possible
    if ($_SESSION['current_level'] > 1) {
        $_SESSION['current_level']--;
    }

    // Reset timer for the new level
    unset($_SESSION['deadline_stage2'], $_SESSION['time_limit_stage2']);

    header('Location: stage2.php');
    exit;
}

$rows = $_SESSION['rows'];
$currentLevel = $_SESSION['current_level'] ?? 1;
$strikes = $_SESSION['strikes'] ?? 0;

// Process POST (user response)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check time
    if (!isset($_SESSION['deadline_stage2']) || time() > $_SESSION['deadline_stage2']) {
        handleStrike(); // counts as failure due to time
    }

    $targetRow = $rows[$currentLevel - 1];
    $guess = $_POST['guess'] ?? [];

    $guessPattern = [];
    for ($i = 0; $i < 5; $i++) {
        $guessPattern[$i] = isset($guess[$i]) ? 1 : 0;
    }

    if ($guessPattern === $targetRow) {
        // Match
        if ($currentLevel >= 5) {
            // Victory
            session_unset();
            session_destroy();
            header('Location: victory.php');
            exit;
        } else {
            $_SESSION['current_level'] = $currentLevel + 1;
            unset($_SESSION['deadline_stage2'], $_SESSION['time_limit_stage2']);
            header('Location: stage2.php');
            exit;
        }
    } else {
        // Failure due to incorrect pattern
        handleStrike();
    }
}

// GET: show current level
$currentLevel = $_SESSION['current_level'];
$strikes = $_SESSION['strikes'] ?? 0;

// Set up timer if it doesn't exist
if (!isset($_SESSION['deadline_stage2']) || !isset($_SESSION['time_limit_stage2'])) {
    $randSeconds = rand(10, 15);
    $_SESSION['time_limit_stage2'] = $randSeconds;
    $_SESSION['deadline_stage2'] = time() + $randSeconds;
}

$remaining = max(0, $_SESSION['deadline_stage2'] - time());
if ($remaining <= 0) {
    // If it's already 0 or less before rendering, count as strike
    handleStrike();
}

$targetRow = $rows[$currentLevel - 1];
?>
<!DOCTYPE html>
<html lang="en"></html>
<head>
    <meta charset="UTF-8">
    <title>Stage 2 - Level <?php echo $currentLevel; ?></title>
    <link rel="stylesheet" href="styles.css">
    <script>
        let remaining = <?php echo $remaining; ?>;
        function startTimer() {
            const timerSpan = document.getElementById('timer');
            const form = document.getElementById('guess-form');

            const interval = setInterval(() => {
                remaining--;
                if (remaining <= 0) {
                    clearInterval(interval);
                    timerSpan.textContent = '0';
                    form.submit(); // submits only when time runs out
                } else {
                    timerSpan.textContent = remaining;
                }
            }, 1000);
        }
        window.onload = startTimer;
    </script>
</head>
<body>
<div class="game-container">
    <h1 class="title">Stage 2: Matching Game</h1>

    <div class="hud">
        <span>Level: <strong><?php echo $currentLevel; ?>/5</strong></span>
        <span>Strikes: <strong><?php echo $strikes; ?>/3</strong></span>
        <span>Time: <strong id="timer"><?php echo $remaining; ?></strong> s</span>
    </div>
    
    <div class="card">
        <h2>Your Attempt</h2>
        <form id="guess-form" method="post">
            <div class="row">
                <span class="row-label">Match the row</span>
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <label class="toggle">
                        <input type="checkbox" name="guess[<?php echo $i; ?>]">
                        <span class="toggle-ui"></span>
                    </label>
                <?php endfor; ?>
            </div>

            <button type="submit" class="btn btn-primary mt">Check</button>
        </form>
    </div>

    <div class="footer-links">
        <a href="start.php?reset=1" class="link">Restart game</a>
    </div>
</div>
</body>
</html>
