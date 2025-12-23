<?php
include '../../../back_button.php';
session_start();

// process the form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check time (deadline created in GET)
    if (!isset($_SESSION['deadline_stage1']) || time() > $_SESSION['deadline_stage1']) {
        session_unset();
        session_destroy();
        header('Location: start.php?gameover=1&reason=time1');
        exit;
    }

    $rows = $_POST['rows'] ?? [];

    // Validate that there are 5 rows and that in each one at least one checkbox is ON
    $valid = true;
    $pattern = [];

    for ($i = 0; $i < 5; $i++) {
        $pattern[$i] = [];
        for ($j = 0; $j < 5; $j++) {
            $isOn = (isset($rows[$i]) && isset($rows[$i][$j])) ? 1 : 0;
            $pattern[$i][$j] = $isOn;
        }
        // Check that this row has at least one 1
        if (array_sum($pattern[$i]) === 0) {
            $valid = false;
        }
    }

    if (!$valid) {
        session_unset();
        session_destroy();
        header('Location: start.php?gameover=1&reason=pattern1');
        exit;
    }

    // Save pattern in session and prepare stage 2
    $_SESSION['rows'] = $pattern;
    $_SESSION['current_level'] = 1;
    $_SESSION['strikes'] = 0;
    unset($_SESSION['deadline_stage2'], $_SESSION['time_limit_stage2']);

    header('Location: stage2.php');
    exit;
}

// initialize stage 1
$_SESSION['deadline_stage1'] = time() + 20; // 20 seconds
$remaining = 20;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stage 1 - Create pattern</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        let remaining = <?php echo $remaining; ?>;
        function startTimer() {
            const timerSpan = document.getElementById('timer');
            const form = document.getElementById('pattern-form');

            const interval = setInterval(() => {
                remaining--;
                if (remaining <= 0) {
                    clearInterval(interval);
                    timerSpan.textContent = '0';
                    form.submit(); // auto-submit when it reaches 0
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
    <h1 class="title">Stage 1: Create your pattern</h1>

    <div class="hud">
        <span>Time remaining: <strong id="timer"><?php echo $remaining; ?></strong> s</span>
    </div>

    <div class="card">
        <p class="description">
            Turn on at least one switch in each row.<br>
            You have 20 seconds. If time runs out, the form will be submitted automatically.
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
