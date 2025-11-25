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
    <?php include 'back_button.php'; ?>
    <h2 class="title">Accountant Form</h2>
    <p class="description">This calculator helps you determine your total earnings based on your base salary.</p>
    <form action="" method="post" class="form-container" id="accountant-form">
        <label for="base_salary">Please entry your base salary: </label>
        <input type="number" id="base_salary" name="base_salary" placeholder="Base Salary" required />
        <input type="hidden" name="rows" value="<?php echo htmlspecialchars($rows); ?>">
        <h3>Overtime Shifts</h3>
        <p>You can add as many overtime rows as you need</p>

        <input type="hidden" name="rows" value="<?php echo htmlspecialchars($rows); ?>">

        <h3>Overtime Shifts</h3>
        <p>You can add as many overtime rows as you need</p>

        <?php for ($i = 0; $i < $rows; $i++):
            $date_value = $ot_dates[$i] ?? '';
            $start_value = $ot_starts[$i] ?? '';
            $end_value = $ot_ends[$i] ?? '';
            ?>
            <div id="ot-row">
                <div>
                    <label>Date:</label>
                    <input type="date" name="ot_date[]" value="<?php echo htmlspecialchars($date_value); ?>">
                </div>
                <div>
                    <label>Start Time:</label>
                    <input type="time" name="ot_start[]" value="<?php echo htmlspecialchars($start_value); ?>">
                </div>
                <div>
                    <label>End Time:</label>
                    <input type="time" name="ot_end[]" value="<?php echo htmlspecialchars($end_value); ?>">
                </div>
            </div>
        <?php endfor; ?>

        <div style="display:flex; gap:1rem; margin-top:1rem;">
            <button type="submit" name="action" value="add_row">+ Add Overtime Row</button>
            <button type="submit" name="action" value="calculate">Calculate</button>
        </div>
    </form>

    <div>
        <h2 class="details">Details</h2>
        <h3 class="steps">Base Salary Breakdown</h3>
        <p class="content">Base salary: <?php echo number_format($base_salary, 2); ?> </p>
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
        <?php if ($action === 'calculate' && $base_salary > 0): ?>
            <h3 class="steps">Overtime Breakdown</h3>

            <?php if (!empty($overtime_rows)): ?>
                <table class="table-container">
                    <tr>
                        <th>Date</th>
                        <th>Hours</th>
                        <th>Hourly Rate</th>
                        <th>Extra Charges</th>
                        <th>Total for Shift</th>
                    </tr>
                    <?php foreach ($overtime_rows as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['date']); ?></td>
                            <td><?php echo number_format($row['hours'], 2); ?></td>
                            <td><?php echo number_format($row['base_rate'], 2); ?></td>
                            <td>
                                <?php
                                $labels = [];
                                if ($row['is_sunday'])
                                    $labels[] = 'Sunday +50%';
                                if ($row['is_night'])
                                    $labels[] = 'Night +25%';
                                echo $labels ? implode(', ', $labels) : 'None';
                                ?>
                            </td>
                            <td><?php echo number_format($row['total'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <p>Total Overtime Pay: <?php echo number_format($total_overtime_pay, 2); ?></p>
            <?php else: ?>
                <p class="content">No overtime shifts registered.</p>
            <?php endif; ?>

            <h3 class="steps">Grand Total Net Salary <?php echo number_format($grand_total, 2); ?></h3>
        <?php endif; ?>
    </div>
</body>

</html>