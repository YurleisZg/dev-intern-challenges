<div class="game-container">
    <h1 class="title">Stage 2: Test your Memory!</h1>

    <div class="hud" style="display: flex; justify-content: space-around; background: #eee; padding: 15px; border-radius: 10px;">
        <span>Level: <strong style="color: #003a70;"><?php echo $session->currentLevel; ?> / 5</strong></span>
        <span>Strikes: <strong style="color: #bd0505;"><?php echo $session->strikes; ?> / 3</strong></span>
        <span>Time: <strong id="timer">--</strong>s</span>
    </div>

    <div class="card" style="margin-top: 20px;">
        <h2 style="text-align: center; margin-bottom: 20px;">Level <?php echo $session->currentLevel; ?></h2>
        <p class="description" style="font-size: 0.9rem;">Replicate the combination of the row corresponding to this level.</p>

        <form id="guess-form" method="post" action="index.php?action=pattern_game&step=stage2">
            <div class="row" style="display: flex; justify-content: center; gap: 10px; padding: 20px; background: #fff; border-radius: 8px;">
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <label class="toggle">
                        <input type="checkbox" name="guess[<?php echo $i; ?>]">
                        <span class="toggle-ui"></span>
                    </label>
                <?php endfor; ?>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
                Verify Combination
            </button>
        </form>
    </div>

    <div style="text-align: center; margin-top: 15px;">
        <a href="index.php?action=pattern_game" style="color: #666; text-decoration: none; font-size: 0.8rem;">Quit game</a>
    </div>
</div>

<script>
    (function() {
        // In a real system, time would be synchronized with the server session
        let timeLeft = 15; 
        const timerDisplay = document.getElementById('timer');
        const form = document.getElementById('guess-form');

        const countdown = setInterval(() => {
            timeLeft--;
            timerDisplay.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(countdown);
                form.submit(); 
            }
        }, 1000);
    })();
</script>