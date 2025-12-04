<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenge 1 - PHP Accountant</title>
    <link rel="stylesheet" href="./css/viewSalaryStyle.css">
    <link rel="stylesheet" href="./css/challenge1Style.css">
</head>
<body>
<header>
    <h1>Challenge 1 · PHP Accountant</h1>
    <a href="../index.php">Home</a>
</header>

<div class="container">
    <form method="POST">
        <p class="page-description">
            🧮 Calcula tu salario mensual con soporte de horas extra usando el mismo estilo
            que el resto de la plataforma (100% PHP, sin JavaScript).
        </p>

        <div class="form-section">
            <div class="section-header">
                <span class="section-title">Salario base</span>
            </div>

            <div class="base-salary-grid">
                <div class="input-group">
                    <label for="gross_salary">Monto mensual bruto *</label>
                    <input
                        type="number"
                        name="gross_salary"
                        id="gross_salary"
                        min="0"
                        step="0.01"
                        required
                        value="<?php echo htmlspecialchars($formData['gross_salary']); ?>"
                    >
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="section-header">
                <span class="section-title">Registros de horas extra</span>
                <span style="font-size: 0.85rem; color: #777;"><?php echo $overtimeRows; ?> registros</span>
            </div>

            <div class="overtime-container">
                <?php for ($i = 0; $i < $overtimeRows; $i++): ?>
                    <div class="overtime-row">
                        <div class="input-group">
                            <label>Fecha</label>
                            <input type="date" name="overtime_date[]" value="<?php echo htmlspecialchars($formData['overtime_date'][$i] ?? ''); ?>">
                        </div>
                        <div class="input-group">
                            <label>Inicio</label>
                            <input type="time" name="overtime_start[]" value="<?php echo htmlspecialchars($formData['overtime_start'][$i] ?? ''); ?>">
                        </div>
                        <div class="input-group">
                            <label>Fin</label>
                            <input type="time" name="overtime_end[]" value="<?php echo htmlspecialchars($formData['overtime_end'][$i] ?? ''); ?>">
                        </div>
                        <?php if ($overtimeRows > 1): ?>
                            <button class="btn-remove" type="submit" name="remove_row" value="<?php echo $i; ?>" title="Eliminar registro">&times;</button>
                        <?php else: ?>
                            <div></div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>

            <button class="btn-add" type="submit" name="add_row" value="1">+ Añadir registro</button>
        </div>

        <button type="submit" name="calculate" class="btn-submit">Calcular salario</button>
    </form>

    <div id="result-container">
        <h2 class="result-header">Resultado</h2>
        <?php if (!$result): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📊</div>
                <p>Completa el formulario y presiona "Calcular salario" para ver el resumen.</p>
            </div>
        <?php else: ?>
            <div class="summary-card">
                <h3>Resumen base</h3>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="summary-label">Salario bruto</div>
                        <div class="summary-value">$<?php echo number_format($result['gross_salary'], 2); ?></div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Impuesto</div>
                        <div class="summary-value">-$<?php echo number_format($result['tax'], 2); ?></div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Salud</div>
                        <div class="summary-value">-$<?php echo number_format($result['health'], 2); ?></div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Bono</div>
                        <div class="summary-value">+$<?php echo number_format($result['bonus'], 2); ?></div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Salario neto base</div>
                        <div class="summary-value">$<?php echo number_format($result['base_salary'], 2); ?></div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Tarifa por hora</div>
                        <div class="summary-value">$<?php echo number_format($result['hourly_rate'], 2); ?></div>
                    </div>
                </div>
            </div>

            <div class="summary-card">
                <h3>Detalle de horas extra</h3>
                <?php if (!empty($result['overtime_data'])): ?>
                    <div class="overtime-list">
                        <?php foreach ($result['overtime_data'] as $shift): ?>
                            <div class="overtime-entry">
                                <div class="overtime-entry-header">
                                    <div>
                                        <span class="overtime-date"><?php echo date('D, M j, Y', strtotime($shift['date'])); ?></span>
                                        <span class="overtime-time"><?php echo $shift['start']; ?> - <?php echo $shift['end']; ?></span>
                                        <?php if ($shift['sunday_bonus'] > 0): ?>
                                            <span class="badge badge-sunday">Domingo</span>
                                        <?php endif; ?>
                                        <?php if ($shift['night_bonus'] > 0): ?>
                                            <span class="badge badge-night">Nocturno</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="summary-value" style="margin: 0;">
                                        $<?php echo number_format($shift['shift_total'], 2); ?>
                                    </div>
                                </div>
                                <div class="overtime-calculation">
                                    <?php echo number_format($shift['hours'], 2); ?> hrs × $<?php echo number_format($shift['total_rate'], 2); ?>/hr
                                    <?php if ($shift['sunday_bonus'] > 0 || $shift['night_bonus'] > 0): ?>
                                        (incluye recargos)
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="overtime-entry" style="background: var(--card); border-style: dashed;">
                            <div class="overtime-entry-header">
                                <div class="summary-label" style="text-transform:none;">Total horas extra</div>
                                <div class="summary-value" style="margin:0;">$<?php echo number_format($result['total_overtime'], 2); ?></div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="empty-state small">
                        No registraste horas extra en este cálculo.
                    </div>
                <?php endif; ?>
            </div>

            <div class="total-highlight">
                <div class="total-value">$<?php echo number_format($result['grand_total'], 2); ?></div>
                <div class="total-subtext">Total neto (base + horas extra)</div>

                <div class="breakdown-grid">
                    <div class="breakdown-item">
                        <div class="breakdown-item-label">Salario base</div>
                        <div class="breakdown-item-value">$<?php echo number_format($result['base_salary'], 2); ?></div>
                    </div>
                    <div class="breakdown-item">
                        <div class="breakdown-item-label">Horas extra</div>
                        <div class="breakdown-item-value">$<?php echo number_format($result['total_overtime'], 2); ?></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

