<?php

$base_salary = $_POST['base_salary'];

function calculate_tax($base_salary) {
    if($base_salary<1000){
        return 0;
    }elseif($base_salary>=1000 && $base_salary<=2000){
        return $base_salary*0.10;
    }elseif($base_salary>2000){
        return $base_salary*0.20;
    }
}

function calculate_health_insurance($base_salary) {
    return $base_salary * 0.05;
}

function calculate_bonus($base_salary):int{
    if($base_salary > 0) {
        return rand(100, 500);
    }
    return 0;
}

$tax = calculate_tax($base_salary);
$health_insurance = calculate_health_insurance($base_salary);
$bonus = calculate_bonus(base64_decode($base_salary));
$final_salary = $base_salary - $tax - $health_insurance + $bonus;
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
    <p class="description">This calculator helps you determine your total earnings based on your base salary.</p>
    <form action="" method="post" class="form-container" id="accountant-form">
        <label for="base_salary">Please entry your base salary: </label>
        <input type="number" id="base_salary" name="base_salary" placeholder="Base Salary" required/>

        <button type="submit" form="accountant-form">calculate</button>
    </form>

   <div >
     <h2 class="details">Details</h2>
     <h3 class="steps">Base Salary Breakdown</h3>
        <p class="content">Base salary:  <?php echo number_format($base_salary, 2); ?> </p>
     <table class="table-container">
        <tr>
            <th>Tax</th>
            <th>Health Insurance</th>
            <th>Bonus</th>
            <th>Final Salary</th>
        </tr>
        <tr>
            <td><?php echo number_format($tax, 2); ?></td>
            <td><?php echo number_format($health_insurance, 2); ?></td>
            <td><?php echo number_format($bonus, 2); ?></td>
            <td><?php echo number_format($final_salary, 2); ?></td>
        </tr>
    </table>
    </div>
</body>

</html>