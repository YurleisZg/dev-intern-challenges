<?php
if (!isset($_SESSION['isAuth']) || $_SESSION['isAuth'] !== true) {
    header('Location: ./login/login.php');
    exit;
}
$isAuth = isset($_SESSION['isAuth']) && $_SESSION['isAuth'] === true;
$editing = isset($currentRecordId) && $currentRecordId !== null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenge 1 - PHP Accountant</title>
    <link rel="stylesheet" href="../css/viewSalaryStyle.css">
    <link rel="stylesheet" href="../css/challenge1Style.css">
</head>
<body>

<header>
    <div class="header-left">
        <h1>Challenge 1 · PHP Accountant</h1>
    </div>
    <div class="header-actions">
        <a href="../../index.php">Dashboard</a>
        <?php if ($isAuth): ?>
            <a href="./login/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        <?php endif; ?>
    </div>
</header>

<div class="records-fab-container">
    <button type="button" class="sidebar-toggle fab" onclick="toggleSidebar()" title="Show saved records">
        <span class="fab-icon">📋</span>
    </button>
</div>
<div class="container page-grid">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>Saved records</h3>
            <button type="button" class="icon-btn" onclick="toggleSidebar()">&times;</button>
        </div>
        <?php if (empty($savedRecords)): ?>
            <p class="empty-state small">You don't have any saved records yet.</p>
        <?php else: ?>
            <div class="records-list">
                <?php foreach ($savedRecords as $record): ?>
                    <div class="record-card">
                        <div class="record-main">
                            <div>
                                <div class="record-id">#<?php echo (int) $record['record_id']; ?></div>
                                <div class="record-meta">$<?php echo number_format($record['gross_salary_input'], 2); ?> · <?php echo (int) $record['detail_count']; ?> hrs</div>
                                <div class="record-date"><?php echo htmlspecialchars($record['updated_at']); ?></div>
                            </div>
                            <div class="record-actions">
                                <a class="btn-link" href="challenge1.php?record_id=<?php echo (int) $record['record_id']; ?>">Edit</a>
                                <form method="POST" onsubmit="return confirm('Delete this record?');">
                                    <input type="hidden" name="delete_record" value="<?php echo (int) $record['record_id']; ?>">
                                    <button type="submit" class="btn-link btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </aside>

    <div class="main-column">
        <?php if (!empty($flash)): ?>
            <div class="flash"><?php echo htmlspecialchars($flash); ?></div>
        <?php endif; ?>

        <?php if ($editing): ?>
            <div class="editing-banner">
                Editing the record #<?php echo (int) $currentRecordId; ?>
                <a href="challenge1.php" class="link-reset">Create new</a>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="record_id" value="<?php echo htmlspecialchars($currentRecordId ?? ''); ?>">
            <p class="page-description">
            </p>

            <div class="form-section">
                <div class="section-header">
                    <span class="section-title">Base Salary</span>
                </div>

                <div class="base-salary-grid">
                    <div class="input-group">
                        <label for="gross_salary">Monthly gross amount *</label>
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
                    <span class="section-title">Overtime records</span>
                    <span style="font-size: 0.85rem; color: #777;"><?php echo $overtimeRows; ?> records</span>
                </div>

                <div class="overtime-container">
                    <?php for ($i = 0; $i < $overtimeRows; $i++): ?>
                        <div class="overtime-row">
                            <div class="input-group">
                                <label>Date</label>
                                <input type="date" name="overtime_date[]" value="<?php echo htmlspecialchars($formData['overtime_date'][$i] ?? ''); ?>">
                            </div>
                            <div class="input-group">
                                <label>Start</label>
                                <input type="time" name="overtime_start[]" value="<?php echo htmlspecialchars($formData['overtime_start'][$i] ?? ''); ?>">
                            </div>
                            <div class="input-group">
                                <label>End</label>
                                <input type="time" name="overtime_end[]" value="<?php echo htmlspecialchars($formData['overtime_end'][$i] ?? ''); ?>">
                            </div>
                            <?php if ($overtimeRows > 1): ?>
                                <button class="btn-remove" type="submit" name="remove_row" value="<?php echo $i; ?>" title="Remove record">&times;</button>
                            <?php else: ?>
                                <div></div>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                </div>

                <button class="btn-add" type="submit" name="add_row" value="1">+ Add record</button>
            </div>

            <div class="actions-row">
                <button type="submit" name="calculate" class="btn-submit">Calculate salary</button>
                <button type="submit" name="<?php echo $editing ? 'update_record' : 'save_record'; ?>" class="btn-save">
                    <?php echo $editing ? 'Save changes' : 'Save record'; ?>
                </button>
            </div>
        </form>
    </div>

    <div id="result-container">
        <h2 class="result-header">Result</h2>
        <?php if (!$result): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📊</div>
                <p>Complete the form and press "Calculate salary" to see the summary.</p>
            </div>
        <?php else: ?>
            <div class="summary-card">
                <h3>Base summary</h3>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="summary-label">Gross salary</div>
                        <div class="summary-value">$<?php echo number_format($result['gross_salary'], 2); ?></div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Tax</div>
                        <div class="summary-value">-$<?php echo number_format($result['tax'], 2); ?></div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Health</div>
                        <div class="summary-value">-$<?php echo number_format($result['health'], 2); ?></div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Bonus</div>
                        <div class="summary-value">+$<?php echo number_format($result['bonus'], 2); ?></div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Base net salary</div>
                        <div class="summary-value">$<?php echo number_format($result['base_salary'], 2); ?></div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Hourly rate</div>
                        <div class="summary-value">$<?php echo number_format($result['hourly_rate'], 2); ?></div>
                    </div>
                </div>
            </div>

            <div class="summary-card">
                <h3>Overtime details</h3>
                <?php if (!empty($result['overtime_data'])): ?>
                    <div class="overtime-list">
                        <?php foreach ($result['overtime_data'] as $shift): ?>
                            <div class="overtime-entry">
                                <div class="overtime-entry-header">
                                    <div>
                                        <span class="overtime-date"><?php echo date('D, M j, Y', strtotime($shift['date'])); ?></span>
                                        <span class="overtime-time"><?php echo $shift['start']; ?> - <?php echo $shift['end']; ?></span>
                                        <?php if ($shift['sunday_bonus'] > 0): ?>
                                            <span class="badge badge-sunday">Sunday</span>
                                        <?php endif; ?>
                                        <?php if ($shift['night_bonus'] > 0): ?>
                                            <span class="badge badge-night">Night</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="summary-value" style="margin: 0;">
                                        $<?php echo number_format($shift['shift_total'], 2); ?>
                                    </div>
                                </div>
                                <div class="overtime-calculation">
                                    <?php echo number_format($shift['hours'], 2); ?> hrs × $<?php echo number_format($shift['total_rate'], 2); ?>/hr
                                    <?php if ($shift['sunday_bonus'] > 0 || $shift['night_bonus'] > 0): ?>
                                        (includes surcharges)
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="overtime-entry" style="background: var(--card); border-style: dashed;">
                            <div class="overtime-entry-header">
                                <div class="summary-label" style="text-transform:none;">Total overtime</div>
                                <div class="summary-value" style="margin:0;">$<?php echo number_format($result['total_overtime'], 2); ?></div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="empty-state small">
                        You did not register overtime in this calculation.
                    </div>
                <?php endif; ?>
            </div>

            <div class="total-highlight">
                <div class="total-value">$<?php echo number_format($result['grand_total'], 2); ?></div>
                <div class="total-subtext">Net total (base + overtime)</div>

                <div class="breakdown-grid">
                    <div class="breakdown-item">
                        <div class="breakdown-item-label">Base salary</div>
                        <div class="breakdown-item-value">$<?php echo number_format($result['base_salary'], 2); ?></div>
                    </div>
                    <div class="breakdown-item">
                        <div class="breakdown-item-label">Overtime</div>
                        <div class="breakdown-item-value">$<?php echo number_format($result['total_overtime'], 2); ?></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleSidebar() {
    document.body.classList.toggle('sidebar-hidden');
}
</script>

</body>
</html>

