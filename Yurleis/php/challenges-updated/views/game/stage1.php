<?php
$timeLimit = 20;
if (!isset($_SESSION['deadline_stage1'])) {
    $_SESSION['deadline_stage1'] = time() + $timeLimit;
}

$remaining = $_SESSION['deadline_stage1'] - time();

if ($remaining < 0) {
    $remaining = 0;
}
?>

<div class="game-container">
    <h1 class="title">Stage 1: Create Your Pattern</h1>

    <div class="hud" style="text-align: center; font-weight: bold; font-size: 1.4rem;">
        Time remaining: <span id="timer" style="color: #bd0505;"><?php echo $remaining; ?></span>s
    </div>

    <div class="card">
        <p class="description" style="font-size: 1rem; margin-bottom: 1.5rem;">
            Activate at least one switch in each row. You have 20 seconds to save.
        </p>

        <div id="error-message" style="color: red; font-weight: bold; margin-bottom: 10px; display: none;">
            You must activate at least one switch in each row!
        </div>

        <form id="pattern-form" method="post" action="index.php?action=pattern_game&step=stage1">
            <div class="grid">
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <div class="row" style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <span class="row-label" style="width: 80px; font-weight: bold; color: #333;">Row <?php echo $i + 1; ?></span>
                        <div style="display: flex; gap: 12px;">
                            <?php for ($j = 0; $j < 5; $j++): ?>
                                <label class="toggle" style="position: relative; display: inline-block; cursor: pointer;">
                                    <input type="checkbox" name="rows[<?php echo $i; ?>][<?php echo $j; ?>]" data-row="<?php echo $i; ?>" style="position: absolute; opacity: 0;">
                                    <span class="toggle-ui" style="
                                        display: block;
                                        width: 40px;
                                        height: 40px;
                                        background: linear-gradient(135deg, #f0f0f0, #e0e0e0);
                                        border: 2px solid #ccc;
                                        border-radius: 8px;
                                        transition: all 0.3s ease;
                                        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                                        position: relative;
                                    "></span>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
                Save Pattern and Start
            </button>
        </form>
    </div>
</div>

<style>
    .toggle input:checked + .toggle-ui {
        background: linear-gradient(135deg, #4CAF50, #45a049) !important;
        border-color: #4CAF50 !important;
        transform: scale(0.95);
        box-shadow: 0 0 15px rgba(76, 175, 80, 0.4);
    }

    .toggle input:checked + .toggle-ui::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-weight: bold;
        font-size: 20px;
    }

    .toggle:hover .toggle-ui {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .toggle input:checked:hover + .toggle-ui {
        transform: scale(1.0);
    }

    .row-error {
        border: 2px solid red !important;
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
</style>

<script>
    (function() {
        let remaining = <?php echo $remaining; ?>;
        const timerSpan = document.getElementById('timer');
        const form = document.getElementById('pattern-form');
        const errorMessage = document.getElementById('error-message');

        function validatePattern() {
            const rows = 5;
            for (let i = 0; i < rows; i++) {
                const rowCheckboxes = document.querySelectorAll(`input[data-row="${i}"]`);
                const hasChecked = Array.from(rowCheckboxes).some(cb => cb.checked);
                if (!hasChecked) {
                    return false;
                }
            }
            return true;
        }

        function highlightEmptyRows() {
            const rows = 5;
            let hasError = false;
            
            for (let i = 0; i < rows; i++) {
                const rowCheckboxes = document.querySelectorAll(`input[data-row="${i}"]`);
                const hasChecked = Array.from(rowCheckboxes).some(cb => cb.checked);
                const rowElements = document.querySelectorAll(`input[data-row="${i}"] + .toggle-ui`);
                
                rowElements.forEach(element => {
                    element.classList.remove('row-error');
                });
                
                if (!hasChecked) {
                    rowElements.forEach(element => {
                        element.classList.add('row-error');
                    });
                    hasError = true;
                }
            }
            
            return hasError;
        }

        form.addEventListener('submit', function(e) {
            if (!validatePattern()) {
                e.preventDefault();
                errorMessage.style.display = 'block';
                highlightEmptyRows();
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                    document.querySelectorAll('.row-error').forEach(el => {
                        el.classList.remove('row-error');
                    });
                }, 3000);
            }
        });

        const interval = setInterval(() => {
            remaining--;
            if (remaining <= 0) {
                clearInterval(interval);
                timerSpan.textContent = '0';
                if (!validatePattern()) {
                    // Game over - redirect to failure page
                    window.location.href = 'index.php?action=pattern_game&result=failed';
                } else {
                    form.submit();
                }
            } else {
                timerSpan.textContent = remaining;
            }
        }, 1000);
    })();
</script>