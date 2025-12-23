

<?php
session_start();
require_once __DIR__ . '/PatternScoreModel.php';

$showForm = true;
$scoreSaved = false;
$scoreNotImproved = false;
$isNewRecord = false;
$previousScore = null;
$error = '';
$topScores = [];

// Calcular tiempo total jugado (usando variable de sesión)
if (!isset($_SESSION['pattern_game_time'])) {
    // Si no existe, usar fallback
    $totalTime = 120; // 2 minutos como fallback
} else {
    $totalTime = $_SESSION['pattern_game_time'];
}

// Lógica de puntaje: mayor puntaje para menor tiempo
// Fórmula: 10000 puntos base - 50 puntos por segundo
// Ejemplo: 30 segundos = 10000 - 1500 = 8500 puntos
//          60 segundos = 10000 - 3000 = 7000 puntos
//          120 segundos = 10000 - 6000 = 4000 puntos
$score = max(10000 - ($totalTime * 50), 100); // Mínimo 100 puntos

// Guardar puntaje si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = trim($_POST['username']);
    if ($username === '') {
        $error = 'El nombre de usuario es obligatorio.';
    } else {
        try {
            $model = new PatternScoreModel();
            // Verificar si el usuario ya existe
            $existingUser = $model->getUserScore($username);
            
            if ($existingUser) {
                $previousScore = $existingUser['score'];
            } else {
                $isNewRecord = true;
            }
            
            // Intentar guardar el puntaje
            $saved = $model->saveScore($username, $score, $totalTime);
            
            if ($saved) {
                $scoreSaved = true;
                $showForm = false;
            } elseif ($existingUser && $previousScore >= $score) {
                $scoreNotImproved = true;
            }
        } catch (Exception $e) {
            $error = 'Error al guardar el puntaje: ' . $e->getMessage();
        }
    }
}

// Obtener top 10 puntajes
try {
    $model = new PatternScoreModel();
    $topScores = $model->getTopScores(10);
} catch (Exception $e) {
    $topScores = [];
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Challenge 3 - Victory</title>
    <link rel="stylesheet" href="pattern_game.css">
</head>
<body>
<header>
    <h1>Challenge 3 · Pattern Memory</h1>
    <a href="../index.php">Home</a>
</header>
<div class="game-container" style="max-width: 1400px; margin: 0 auto;">
    <h1 style="margin: 15px 0 20px 0; font-size: 28px;">🎉 You Did It!</h1>
    
    <div style="display: grid; grid-template-columns: 65% 35%; gap: 15px; margin-bottom: 20px; height: fit-content;">
        <!-- LEFT COLUMN: Score Result + Score Explanation -->
        <div>
            <!-- Score Result & Explanation Container -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 12px; margin-bottom: 15px;">
                
                <!-- Score Result Card (Compact) -->
                <div class="card card-success" style="padding: 12px;">
                    <h2 style="margin: 0 0 10px 0; font-size: 16px;">🏆 Score</h2>
                    
                    <div style="text-align: center; padding: 12px; background: linear-gradient(135deg, #f39c12, #e67e22); border-radius: 6px; margin-bottom: 10px; color: white;">
                        <div style="font-size: 38px; font-weight: bold; margin-bottom: 4px;"><?php echo number_format($score); ?></div>
                        <div style="font-size: 11px; font-weight: 600; opacity: 0.9;">POINTS</div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px; margin-bottom: 10px;">
                        <div style="background-color: #ecf0f1; padding: 8px; border-radius: 5px; text-align: center;">
                            <div style="font-size: 20px; color: #2ecc71; font-weight: bold;"><?php echo $totalTime; ?>s</div>
                            <div style="font-size: 9px; color: #7f8c8d; margin-top: 2px;">TIME</div>
                        </div>
                        <div style="background-color: #ecf0f1; padding: 8px; border-radius: 5px; text-align: center;">
                            <div style="font-size: 20px; color: #3498db; font-weight: bold;">5/5</div>
                            <div style="font-size: 9px; color: #7f8c8d; margin-top: 2px;">LEVELS</div>
                        </div>
                    </div>

                    <?php if ($showForm): ?>
                        <form method="post" style="margin-bottom: 8px;">
                            <input type="text" name="username" maxlength="100" placeholder="Username" required style="width: 100%; padding: 7px; margin-bottom: 6px; border: 2px solid #bdc3c7; border-radius: 4px; font-size: 12px; box-sizing: border-box;">
                            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 7px; font-size: 12px;">Save</button>
                        </form>
                        <?php if ($error): ?><p style="color:red; text-align: center; font-weight: bold; font-size: 11px; margin: 4px 0;">⚠️ <?php echo $error; ?></p><?php endif; ?>
                    <?php elseif ($scoreSaved): ?>
                        <div style="padding: 8px; background-color: #d5f4e6; border-left: 3px solid #27ae60; border-radius: 4px; font-size: 11px;">
                            <p style="color: #27ae60; font-weight: bold; margin: 0;">✓ Saved!</p>
                            <?php if ($isNewRecord): ?>
                                <p style="color: #27ae60; margin: 3px 0;">First record</p>
                            <?php else: ?>
                                <p style="color: #27ae60; margin: 3px 0;">Record 🎉 <strong style="color: #f39c12;">+<?php echo $score - $previousScore; ?></strong></p>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($scoreNotImproved): ?>
                        <div style="padding: 8px; background-color: #fadbd8; border-left: 3px solid #e74c3c; border-radius: 4px; font-size: 11px;">
                            <p style="color: #e74c3c; font-weight: bold; margin: 0;">✗ Not Saved</p>
                            <p style="color: #e74c3c; margin: 3px 0;">Prev: <strong><?php echo $previousScore; ?></strong> | Diff: <strong style="color: #c0392b;">-<?php echo $previousScore - $score; ?></strong></p>
                        </div>
                    <?php endif; ?>

                    <a href="challenge3.php?reset=1" class="btn btn-primary" style="width: 100%; text-align: center; display: block; padding: 7px; font-size: 12px;">Play Again</a>
                </div>

                <!-- Score Explanation Card -->
                <div class="card" style="background: linear-gradient(135deg, #ecf0f1, #dfe6e9); padding: 12px;">
                    <h2 style="margin: 0 0 10px 0; font-size: 16px;">📊 Your Score</h2>
                    
                    <div style="font-size: 11px; color: #2c3e50; line-height: 1.6;">
                        <div style="margin-bottom: 8px;">
                            <div style="font-weight: bold; color: #34495e; margin-bottom: 3px;">Formula:</div>
                            <div style="background: white; padding: 6px; border-radius: 4px; font-family: monospace; font-size: 10px; text-align: center; border-left: 3px solid #3498db;">
                                10000 − (time × 50)
                            </div>
                        </div>

                        <div style="margin-bottom: 8px;">
                            <div style="font-weight: bold; color: #34495e; margin-bottom: 3px;">Your Calculation:</div>
                            <div style="background: white; padding: 6px; border-radius: 4px; font-size: 10px; text-align: center; border-left: 3px solid #f39c12;">
                                10000 − (<?php echo $totalTime; ?> × 50) = <strong><?php echo number_format($score); ?></strong>
                            </div>
                        </div>

                        <div>
                            <div style="font-weight: bold; color: #34495e; margin-bottom: 3px;">💡 What it means:</div>
                            <ul style="margin: 0; padding-left: 16px;">
                                <li>Faster times earn more points</li>
                                <li>Every second costs 50 pts</li>
                                <li>Min guarantee: 100 pts</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Leaderboard -->
        <div class="card" style="height: fit-content; padding: 12px;">
            <h2 style="margin: 0 0 10px 0; font-size: 16px;">🏆 Leaderboard - Top 10</h2>
            <div style="overflow-y: auto; max-height: 550px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #3498db, #2980b9); color: white; text-align: left; position: sticky; top: 0;">
                            <th style="padding: 7px; border: none; font-weight: bold; width: 28px; text-align: center; font-size: 11px;">#</th>
                            <th style="padding: 7px; border: none; font-weight: bold; font-size: 11px;">Player</th>
                            <th style="padding: 7px; border: none; font-weight: bold; width: 75px; text-align: right; font-size: 11px;">Score</th>
                            <th style="padding: 7px; border: none; font-weight: bold; width: 55px; text-align: right; font-size: 11px;">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topScores as $i => $row): ?>
                            <tr style="border-bottom: 1px solid #ecf0f1; background-color: <?php echo ($i % 2 === 0) ? '#ffffff' : '#f8f9fa'; ?>;">
                                <td style="padding: 7px; border: none; font-weight: bold; text-align: center;">
                                    <?php if ($i === 0): ?>
                                        <span style="font-size: 14px;">🥇</span>
                                    <?php elseif ($i === 1): ?>
                                        <span style="font-size: 14px;">🥈</span>
                                    <?php elseif ($i === 2): ?>
                                        <span style="font-size: 14px;">🥉</span>
                                    <?php else: ?>
                                        <span style="color: #7f8c8d; font-size: 10px;"><?php echo $i+1; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 7px; border: none; font-weight: 600; color: #2c3e50; font-size: 11px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100px;"><?php echo htmlspecialchars(substr($row['username'], 0, 16)); ?></td>
                                <td style="padding: 7px; border: none; font-weight: bold; color: #f39c12; font-size: 12px; text-align: right;"><?php echo number_format($row['score']); ?></td>
                                <td style="padding: 7px; border: none; color: #7f8c8d; font-size: 10px; text-align: right;"><?php echo $row['time_seconds']; ?>s</td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($topScores)): ?>
                            <tr>
                                <td colspan="4" style="padding: 20px 7px; text-align: center; color: #7f8c8d; font-size: 11px;">No scores yet. Be the first!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>


