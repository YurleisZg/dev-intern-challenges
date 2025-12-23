<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pattern Timer Demo</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<div class="container">
    <h2>Pattern Builder · Timer Preview</h2>
    <div class="timer">Time remaining: <span id="time">20</span>s</div>

    <form method="POST" action="pattern_timer_handler.php">
        <?php for ($i = 1; $i <= 5; $i++) : ?>
            <div class="row">
                <?php for ($j = 1; $j <= 5; $j++) : ?>
                    <label>
                        <input type="checkbox" name="row<?= $i ?>[]" value="<?= $j ?>">
                    </label>
                <?php endfor; ?>
            </div>
        <?php endfor; ?>

        <button type="submit">Save pattern</button>
    </form>
</div>

<script>
    let time = 20;
    const span = document.getElementById("time");
    const interval = setInterval(() => {
        time--;
        span.textContent = time;
        if (time <= 0) {
            clearInterval(interval);
            document.forms[0].submit();
        }
    }, 1000);
</script>
</body>
</html>


