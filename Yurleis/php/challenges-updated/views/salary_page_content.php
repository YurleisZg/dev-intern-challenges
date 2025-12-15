<?php 
?>

<div class="content-header">
    <p> Enter your monthly base salary and your overtime shifts. We’ll calculate 
        your net salary, including <strong>bonuses</strong> 
        and <strong>taxes &amp; deductions</strong>.</p>
</div>

<section class="rules-panel">
    <h3>Overtime rules</h3>
    <ul>
        <li><strong>Monday to Friday:</strong> overtime between <strong>18:00</strong> and <strong>06:00</strong>.</li>
        <li><strong>Saturday:</strong> overtime from <strong>13:00</strong> until <strong>23:59</strong>.</li>
        <li><strong>Sunday:</strong> all hours are treated as overtime.</li>
        <li>If a shift has only normal hours, it will <strong>not</strong> appear in the overtime table.</li>
    </ul>
</section>

<main class="main-layout">
    <section class="card form-card">
        <h2 class="card-title">1. Enter your data</h2>

        <form action="" method="POST">
            
            <div class="field">
                <label for="base_salary">Base Salary (Monthly)</label>
                <input type="number" step="0.01" id="base_salary" name="base_salary" 
                       value="<?php echo htmlspecialchars($base_salary ?? 0.0); ?>" 
                       required>
                <?php if (isset($errors['base_salary'])): ?>
                    <p class="error-message"><?php echo htmlspecialchars($errors['base_salary']); ?></p>
                <?php endif; ?>
            </div>

            <h3 class="steps">Overtime Shifts</h3>
            
            <input type="hidden" name="rows" value="<?php echo htmlspecialchars($rows ?? 0); ?>">

            <?php 
            // Aseguramos que $rows esté definido y sea un número
            $numRows = (int)($rows ?? 0);
            for ($i = 0; $i < $numRows; $i++): 
                $dateVal = htmlspecialchars($date[$i] ?? '');
                $startVal = htmlspecialchars($start_time[$i] ?? '');
                $endVal = htmlspecialchars($end_time[$i] ?? '');
            ?>
                <fieldset class="overtime-block">
                    <legend>Shift #<?php echo $i + 1; ?></legend>
                    
                    <div class="field">
                        <label for="date_overtime_<?php echo $i; ?>">Date</label>
                        <input type="date" id="date_overtime_<?php echo $i; ?>" 
                               name="date_overtime[]" value="<?php echo $dateVal; ?>">
                    </div>
                    <div class="field">
                        <label for="start_time_overtime_<?php echo $i; ?>">Start Time</label>
                        <input type="time" id="start_time_overtime_<?php echo $i; ?>" 
                               name="start_time_overtime[]" value="<?php echo $startVal; ?>">
                    </div>
                    <div class="field">
                        <label for="end_time_overtime_<?php echo $i; ?>">End Time</label>
                        <input type="time" id="end_time_overtime_<?php echo $i; ?>" 
                               name="end_time_overtime[]" value="<?php echo $endVal; ?>">
                    </div>
                </fieldset>
            <?php endfor; ?>
            
            <div class="button-row">
                <button type="submit" name="action" value="add_overtime" class="btn btn-primary">Add Shift</button>
                <?php if ($numRows > 0): ?>
                    <button type="submit" name="action" value="remove_overtime" class="btn btn-red">Remove Last Shift</button>
                <?php endif; ?>
            </div>
            
            <hr>

            <button type="submit" name="action" value="calculate" class="btn btn-full btn-primary" style="margin-top: 15px;">
                CALCULATE & SAVE SALARY
            </button>
        </form>
    </section>

    <section class="card results-card">
        <h2 class="card-title">2. Results</h2>

        <?php if (!$has_results): ?>
            <p class="placeholder-text">Please enter your data and click 'Calculate & Save Salary' to see the results.</p>
        <?php else: ?>
          <div class="button-row" style="margin-bottom: 5px;">
             <a href="index.php?action=salary&clear=1" class="btn btn-red">Clear Results</a>
         </div>
            
            <h3 class="steps">Base Salary Breakdown</h3>
            <table class="table-container">
                <tr>
                    <th>Gross Salary</th>
                    <th>(-) Tax</th>
                    <th>(-) Health Insurance</th>
                    <th>(+) Bonus</th>
                    <th>Net Base Salary</th>
                </tr>
                <tr>
                    <td class="amount neutral"><?php echo number_format($base_salary, 2); ?></td>
                    <td class="amount negative"><?php echo number_format($tax, 2); ?></td>
                    <td class="amount negative"><?php echo number_format($health_insurance, 2); ?></td>
                    <td class="amount positive"><?php echo number_format($bonus, 2); ?></td>
                    <td class="amount strong"><?php echo number_format($final_base_net, 2); ?></td>
                </tr>
            </table>

            <?php if (!empty($overtime_details)): ?>
                <h3 class="steps" style="margin-top:2rem;">Overtime Details</h3>
                <table class="table-container">
                    <tr>
                        <th>Date</th>
                        <th>Hours</th>
                        <th>Base Rate</th>
                        <th>Premium Details</th>
                        <th>Total Pay</th>
                    </tr>

                    <?php foreach ($overtime_details as $ot): ?>
                        <tr>
                            <td class="date-cell"><?php echo htmlspecialchars($ot['date']); ?></td>
                            <td><?php echo number_format($ot['hours'], 2); ?></td>
                            <td><?php echo number_format($ot['base_rate'], 2); ?></td>
                            <td>
                                <?php if (!empty($ot['extra_labels'])): ?>
                                    <ul class="premium-list">
                                        <?php foreach ($ot['extra_labels'] as $label): ?>
                                            <li><?php echo htmlspecialchars($label); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    Base hours (1.0x)
                                <?php endif; ?>
                            </td>
                            <td class="amount positive">
                                <?php echo number_format($ot['total'], 2); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <tr class="summary-row">
                        <td colspan="4" class="summary-label-cell">TOTAL OVERTIME PAY</td>
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

        <h3 class="steps" style="margin-top:2rem;">Salary Calculation History</h3>
        
        <?php if (empty($records)): ?>
            <p class="placeholder-text">No saved calculations. Calculate your first salary!</p>
        <?php else: ?>
            <table class="table-container" style="text-align: left; margin-top: 10px;">
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Net Total</th>
                <th>Actions</th>
                </tr>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?php echo $record->getId(); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($record->getCreatedAt())); ?></td>
                        <td class="amount strong"><?php echo number_format($record->getGrandTotalSalary(), 2); ?></td>
                        <td>
                            <a href="index.php?action=view&id=<?php echo $record->getId(); ?>" class="btn btn-green" style="padding: 5px 10px;">Cargar</a>
                            
                            <a href="index.php?action=delete&id=<?php echo $record->getId(); ?>" class="btn btn-red" style="padding: 5px 10px;" onclick="return confirm('¿Eliminar registro #<?php echo $record->getId(); ?>?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

    </section>
</main>