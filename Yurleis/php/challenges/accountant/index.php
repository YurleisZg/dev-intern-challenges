<?php
include 'operations.php';
include '../../../back_button.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary & Overtime Calculator</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
<div class="app-shell">
    <header class="app-header">
        <div>
            <h1 class="app-title">Salary & Overtime Calculator</h1>
            <p class="app-subtitle">
                Enter your monthly base salary and your overtime shifts. We’ll calculate 
                your net salary, including <strong>bonuses</strong> 
                and <strong>taxes &amp; deductions</strong>.
            </p>
        </div>
    </header>

    <section class="rules-panel">
        <h2>Overtime rules</h2>
        <ul>
            <li><strong>Monday to Friday:</strong> overtime between <strong>18:00</strong> and <strong>06:00</strong>.</li>
            <li><strong>Saturday:</strong> overtime from <strong>13:00</strong> until <strong>23:59</strong>.</li>
            <li><strong>Sunday:</strong> all hours are treated as overtime.</li>
            <li>If a shift has only normal hours, it will <strong>not</strong> appear in the overtime table.</li>
        </ul>
    </section>

    <main class="main-layout">
        <!-- FORM -->
        <section class="card form-card">
            <h2 class="card-title">1. Enter your data</h2>

            <form action="" method="post" id="accountant-form">
                <div class="field-full">
                    <label for="base_salary">
                        Base monthly salary 
                        <span class="label-hint">(before taxes, in your local currency)</span>
                    </label>
                    <input
                        type="number"
                        step="0.01"
                        min="1"
                        max="9999999"
                        id="base_salary"
                        name="base_salary"
                        placeholder="e.g. 1500.00"
                        value="<?php echo htmlspecialchars($base_salary); ?>"
                        required
                    />
                    <small class="help-text">
                        Gross salary for one month. Use a dot for decimals (e.g. 1500.50).
                    </small>
                    <?php if (!empty($errors['base_salary'])): ?>
                        <small class="error-text">
                            <?php echo htmlspecialchars($errors['base_salary']); ?>
                        </small>
                    <?php endif; ?>
                </div>

                <div class="field-full">
                    <label>
                        Overtime shifts
                        <span class="label-note">(you can add as many as you need)</span>
                    </label>
                    <div class="button-row">
                        <button type="submit" name="action" value="add_overtime" class="btn btn-green">
                            + Add overtime shift
                        </button>
                        <button type="submit" name="action" value="remove_overtime" class="btn btn-red" formnovalidate> - Remove overtime shift</button>
        
                    </div>
                </div>

                <input type="hidden" name="rows" value="<?php echo htmlspecialchars($rows); ?>">

                <?php for ($i = 0; $i < $rows; $i++): ?>
                    <fieldset class="overtime-block">
                        <legend>Overtime shift #<?php echo $i + 1; ?></legend>

                        <div class="field">
                            <label for="date_overtime_<?php echo $i; ?>">
                                Date of the shift
                            </label>
                            <input
                                type="date"
                                id="date_overtime_<?php echo $i; ?>"
                                required
                                name="date_overtime[]"
                                value="<?php echo htmlspecialchars($date[$i] ?? ''); ?>"
                            />
                            <small class="help-text">
                                Select the calendar date when you worked this shift.
                            </small>
                        </div>

                        <div class="field">
                            <label for="start_time_overtime_<?php echo $i; ?>">
                                Start time (24-hour format)
                            </label>
                            <input
                                type="time"
                                id="start_time_overtime_<?php echo $i; ?>"
                                name="start_time_overtime[]"
                                required
                                value="<?php echo htmlspecialchars($start_time[$i] ?? ''); ?>"
                            />
                            <small class="help-text">
                                Example: 13:00, 18:30, 20:00.
                            </small>
                        </div>

                        <div class="field">
                            <label for="end_time_overtime_<?php echo $i; ?>">
                                End time (24-hour format)
                            </label>
                            <input
                                type="time"
                                required
                                id="end_time_overtime_<?php echo $i; ?>"
                                name="end_time_overtime[]"
                                value="<?php echo htmlspecialchars($end_time[$i] ?? ''); ?>"
                            />
                            <small class="help-text">
                                If the shift ends after midnight, use the time of the next day.
                                Example: start 22:00, end 02:00.
                            </small>
                        </div>
                    </fieldset>
                <?php endfor; ?>

                <div class="field-full" style="margin-top: 1rem;">
                    <button type="submit" name="action" value="calculate" class="btn btn-primary btn-full">
                        Calculate salary
                    </button>
                </div>
            </form>
        </section>

        <!-- RESULTS -->
        <section class="card results-card">
            <h2 class="card-title">2. Results</h2>

            <?php if (!$has_results): ?>
                <p class="placeholder-text">
                    👉 Enter your base salary and at least one overtime shift, then click 
                    <strong>“Calculate salary”</strong> to see the breakdown here.
                </p>
            <?php endif; ?>

            <?php if ($has_results): ?>

                <h3 class="steps">Base Salary Breakdown</h3>
                <table class="table-container">
                    <tr>
                        <th>Tax</th>
                        <th>Health Insurance</th>
                        <th>Bonus</th>
                        <th>Net Base Salary</th>
                    </tr>
                    <tr>
                        <td class="amount negative"><?php echo number_format($tax, 2); ?></td>
                        <td class="amount negative"><?php echo number_format($health_insurance, 2); ?></td>
                        <td class="amount positive"><?php echo number_format($bonus, 2); ?></td>
                        <td class="amount neutral"><?php echo number_format($final_base_net, 2); ?></td>
                    </tr>
                </table>

                <?php if (!empty($overtime_details)): ?>
                    <h3 class="steps" style="margin-top: 2rem;">Overtime Breakdown</h3>

                    <table class="table-container">
                        <tr>
                            <th>Date</th>
                            <th>Overtime Hours</th>
                            <th>Base Rate</th>
                            <th>Extra Info &amp; Charges</th>
                            <th>Base Overtime Pay</th>
                            <th>Amount Breakdown</th>
                            <th>Total</th>
                        </tr>

                        <?php foreach ($overtime_details as $ot): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($ot['date']); ?></td>
                                <td><?php echo number_format($ot['hours'], 2); ?></td>
                                <td><?php echo number_format($ot['base_rate'], 2); ?></td>

                                <td>
                                    <?php echo implode("<br>", array_map('htmlspecialchars', $ot['extra_labels'])); ?>
                                </td>

                                <td class="amount positive">
                                    <?php echo number_format($ot['base_pay'], 2); ?>
                                </td>

                                <td>
                                    <?php if ($ot['extra_sunday'] > 0): ?>
                                        <span class="amount positive">
                                            Sunday / holiday extra:
                                            <?php echo number_format($ot['extra_sunday'], 2); ?>
                                        </span><br>
                                    <?php endif; ?>

                                    <?php if ($ot['extra_night'] > 0): ?>
                                        <span class="amount positive">
                                            Night shift extra:
                                            <?php echo number_format($ot['extra_night'], 2); ?>
                                        </span><br>
                                    <?php endif; ?>

                                    <span class="amount positive">
                                        Extra total:
                                        <?php echo number_format($ot['extra_pay'], 2); ?>
                                    </span>
                                </td>

                                <td class="amount positive">
                                    <?php echo number_format($ot['total'], 2); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <tr class="summary-row">
                            <td colspan="6" class="summary-label-cell">TOTAL OVERTIME PAY</td>
                            <td class="amount positive">
                                <?php echo number_format($total_overtime_payment, 2); ?>
                            </td>
                        </tr>
                    </table>
                <?php endif; ?>

                <h3 class="steps" style="margin-top:2rem;">Grand Total Net Salary</h3>
                <table class="table-container">
                    <tr>
                        <th>Net Base Salary</th>
                        <th>Total Overtime Payment</th>
                        <th>Final Salary</th>
                    </tr>
                    <tr>
                        <td class="amount neutral"><?php echo number_format($final_base_net, 2); ?></td>
                        <td class="amount positive"><?php echo number_format($total_overtime_payment, 2); ?></td>
                        <td class="amount strong"><?php echo number_format($grand_total_salary, 2); ?></td>
                    </tr>
                </table>

            <?php endif; ?>
        </section>
    </main>
</div>
</body>
</html>
