<?php
include 'operations.php';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Accountant Form</title>
</head>

<body>
    <h2 class="title">Accountant Form</h2>
    <div class="container">
        <form action="" method="post" class="form-container" id="accountant-form">
            <div class="field-full">
                <label for="base_salary">Please entry your base salary:</label>
                <input type="number" id="base_salary" name="base_salary" placeholder="Base Salary"
                    value="<?php echo htmlspecialchars($base_salary); ?>" required />
            </div>
            <label class="field-full">You can entry overtime hours as you need</label>

            <div class="field-full">
                <button type="submit" name="action" value="add_overtime">Add</button>
            </div>

            <input type="hidden" name="rows" value="<?php echo htmlspecialchars($rows); ?>">

            <?php for ($i = 0; $i < $rows; $i++): ?>
                <div class="field">
                    <label for="date_overtime_<?php echo $i; ?>">Date</label>
                    <input type="date" id="date_overtime_<?php echo $i; ?>" name="date_overtime[]"
                        value="<?php echo htmlspecialchars($date[$i]); ?>">
                </div>

                <div class="field">
                    <label for="start_time_overtime_<?php echo $i; ?>">Start time</label>
                    <input type="time" id="start_time_overtime_<?php echo $i; ?>" name="start_time_overtime[]"
                        value="<?php echo htmlspecialchars($start_time[$i]); ?>">
                </div>

                <div class="field">
                    <label for="end_time_overtime_<?php echo $i; ?>">End time</label>
                    <input type="time" id="end_time_overtime_<?php echo $i; ?>" name="end_time_overtime[]"
                        value="<?php echo htmlspecialchars($end_time[$i]); ?>">
                </div>
            <?php endfor; ?>
            <button type="submit" name="action" value="calculate">Calculate</button>
    </div>
    </form>

<?php if ($action === 'calculate' && $base_salary > 0): ?>
    <div class="details-container">
        <h2 class="details">Details</h2>

        <h3 class="steps">Base Salary Breakdown</h3>
        <table class="table-container">
            <tr>
                <th>Tax</th>
                <th>Health Insurance</th>
                <th>Bonus</th>
                <th>Net Base Salary</th>
            </tr>
            <tr>
                <td><?php echo number_format($tax, 2); ?></td>
                <td><?php echo number_format($health_insurance, 2); ?></td>
                <td><?php echo number_format($bonus, 2); ?></td>
                <td><?php echo number_format($final_base_net, 2); ?></td>
            </tr>
        </table>


        <?php if (!empty($overtime_details)): ?>
            <h3 class="steps" style="margin-top: 2rem;">Overtime Breakdown</h3>

            <table class="table-container">
                <tr>
                    <th>Date</th>
                    <th>Hours</th>
                    <th>Base Rate</th>
                    <th>Extra Charges</th>
                    <th>Effective Rate</th>
                    <th>Total</th>
                </tr>

                <?php foreach ($overtime_details as $ot): ?>
                    <tr>
                        <td><?php echo $ot['date']; ?></td>
                        <td><?php echo number_format($ot['hours'], 2); ?></td>
                        <td><?php echo number_format($ot['base_rate'], 2); ?></td>
                        <td>
                            <?php echo implode("<br>", $ot['extra_labels']); ?>
                        </td>
                        <td><?php echo number_format($ot['effective_rate'], 2); ?></td>
                        <td><?php echo number_format($ot['total'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>

                <tr style="font-weight: bold; background:#eaeaea;">
                    <td colspan="5">TOTAL OVERTIME PAY</td>
                    <td><?php echo number_format($total_overtime_payment, 2); ?></td>
                </tr>
            </table>
        <?php endif; ?>


        <h3 class="steps">Grand Total Net Salary</h3>
        <table class="table-container">
            <tr>
                <th>Net Base Salary</th>
                <th>Total Overtime Payment</th>
                <th>Final Salary</th>
            </tr>
            <tr>
                <td><?php echo number_format($final_base_net, 2); ?></td>
                <td><?php echo number_format($total_overtime_payment, 2); ?></td>
                <td><?php echo number_format($grand_total_salary, 2); ?></td>
            </tr>
        </table>

    </div>
<?php endif; ?>


</body>

</html>