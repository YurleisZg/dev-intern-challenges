<?php
session_start();

// Handle pattern submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check time window created in the session
    if (!isset($_SESSION['pattern_setup_deadline']) || time() > $_SESSION['pattern_setup_deadline']) {
        session_unset();
        session_destroy();
        header('Location: challenge3.php?gameover=1&reason=time1');
        exit;
    }

    $rows = $_POST['rows'] ?? [];

    $isValidPattern = true;
    $pattern = [];

    // Build a 5x5 pattern where each row must have at least one active toggle
    for ($i = 0; $i < 5; $i++) {
        $pattern[$i] = [];
        for ($j = 0; $j < 5; $j++) {
            $isOn = (isset($rows[$i]) && isset($rows[$i][$j])) ? 1 : 0;
            $pattern[$i][$j] = $isOn;
        }

        if (array_sum($pattern[$i]) === 0) {
            $isValidPattern = false;
        }
    }

    if (!$isValidPattern) {
        session_unset();
        session_destroy();
        header('Location: challenge3.php?gameover=1&reason=pattern1');
        exit;
    }

    // Store the pattern and initialize the challenge stage
    $_SESSION['rows'] = $pattern;
    $_SESSION['current_level'] = 1;
    $_SESSION['strikes'] = 0;
    unset($_SESSION['pattern_challenge_deadline'], $_SESSION['pattern_challenge_time_limit']);

    header('Location: pattern_play.php');
    exit;
}

// Initialize pattern setup timer
$_SESSION['pattern_setup_deadline'] = time() + 20; // 20 seconds
$remainingSeconds = 20;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Challenge 3 - Pattern Setup</title>
    <link rel="stylesheet" href="pattern_game.css">
    <script>
        let remaining = <?php echo $remainingSeconds; ?>;
        function startTimer() {
            const timerSpan = document.getElementById('timer');
            const form = document.getElementById('pattern-form');

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
    <h1 class="title">Pattern Setup</h1>

    <div class="hud">
        <span>Time left: <strong id="timer"><?php echo $remainingSeconds; ?></strong> s</span>
    </div>

    <div class="card">
        <p class="description">
            Turn on at least one toggle in each row to define your secret pattern.<br>
            You have 20 seconds; when the timer reaches zero, the pattern will be submitted automatically.
        </p>

        <form id="pattern-form" method="post">
            <div class="grid">
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <div class="row">
                        <span class="row-label">Row <?php echo $i + 1; ?></span>
                        <?php for ($j = 0; $j < 5; $j++): ?>
                            <label class="toggle">
                                <input type="checkbox" name="rows[<?php echo $i; ?>][<?php echo $j; ?>]">
                                <span class="toggle-ui"></span>
                            </label>
                        <?php endfor; ?>
                    </div>
                <?php endfor; ?>
            </div>

            <button type="submit" class="btn btn-primary mt">Save pattern and continue</button>
        </form>
    </div>
</div>
</body>
</html>


