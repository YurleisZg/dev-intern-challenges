<div class="game-container" style="text-align: center;">
    <div class="card card-error" style="border-top: 5px solid #bd0505; padding: 3rem;">
        <div style="font-size: 4rem; margin-bottom: 1rem;">💀</div>
        <h1 class="title" style="color: #bd0505; margin-bottom: 0.5rem;">Game Over!</h1>
        
        <div class="description" style="margin-bottom: 2rem;">
            <?php 
            // Determine the message based on the reason saved in session or state
            if (isset($_GET['reason'])) {
                switch ($_GET['reason']) {
                    case 'timeout':
                        echo "Time has run out. You need to be faster next time!";
                        break;
                    case 'pattern1':
                        echo "Invalid pattern: You must activate at least one switch per row.";
                        break;
                    default:
                        echo "You couldn't complete the pattern correctly.";
                }
            } elseif ($session && $session->strikes >= 3) {
                echo "You have accumulated <strong>3 strikes</strong>. Memory has failed you this time.";
            } else {
                echo "You failed to overcome the challenge.";
            }
            ?>
        </div>

        <div class="stats-box" style="background: #fff; padding: 15px; border-radius: 10px; margin-bottom: 2rem; display: inline-block; min-width: 200px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
            <p style="margin: 5px 0;">Level reached: <strong><?php echo $session->currentLevel ?? 1; ?> / 5</strong></p>
            <p style="margin: 5px 0;">Total strikes: <strong><?php echo $session->strikes ?? 3; ?></strong></p>
        </div>
        
        <div class="button-row" style="justify-content: center; gap: 15px;">
            <a href="index.php?action=pattern_game&step=stage1" class="btn btn-red">Try Again</a>
            <a href="index.php?action=pattern_game" class="btn" style="background: #626262; color: white;">Back to Start</a>
        </div>
    </div>
</div>