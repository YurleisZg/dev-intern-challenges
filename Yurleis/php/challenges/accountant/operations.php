<?php
$action = $_POST['action'] ?? '';

$base_salary = isset($_POST['base_salary']) ? (float) $_POST['base_salary'] : 0.0;

$date = $_POST['date_overtime'] ?? [];
$start_time = $_POST['start_time_overtime'] ?? [];
$end_time = $_POST['end_time_overtime'] ?? [];

$rows = isset($_POST['rows']) ? (int) $_POST['rows'] : 0;
if ($rows < 0) {
    $rows = 0;
}

if ($action === 'add_overtime') {
    $rows++;
}

function calculate_tax($base_salary)
{
    if ($base_salary < 1000) {
        return 0;
    } elseif ($base_salary >= 1000 && $base_salary <= 2000) {
        return $base_salary * 0.10;
    }
    return $base_salary * 0.20;
}
function calculate_health_insurance($base_salary)
{
    return $base_salary * 0.05;
}

function calculate_bonus($base_salary): int
{
    if ($base_salary > 0) {
        return rand(100, 500);
    }
    return 0;
}

function calculate_overtime_payment($base_salary, $date, $start_time, $end_time)
{
    $hourly_rate = $base_salary / 160;

    $start = new DateTime("$date $start_time");
    $end = new DateTime("$date $end_time");

    if ($end <= $start) {
        $end->modify('+1 day');
    }

    $interval = $start->diff($end);
    $hours = $interval->h + ($interval->i / 60);
    $extra_factor = 0.0;

    $day_of_week = (int) $start->format('N');
    if ($day_of_week === 7) {
        $extra_factor += 0.50;
    }

    $start_time_str = $start->format('H:i');
    if ($start_time_str >= '18:00') {
        $extra_factor += 0.25;
    }

    $effective_rate = $hourly_rate * (1 + $extra_factor);
    return $hours * $effective_rate;
}


$tax = 0;
$health_insurance = 0;
$bonus = 0;
$final_base_net = 0;

if ($action === 'calculate' && $base_salary > 0) {
    $tax = calculate_tax($base_salary);
    $health_insurance = calculate_health_insurance($base_salary);
    $bonus = calculate_bonus($base_salary);
    $final_base_net = $base_salary - $tax - $health_insurance + $bonus;
}

$overtime_details = [];
$total_overtime_payment = 0;

if ($action === 'calculate' && $rows > 0) {

    $hourly_rate = $base_salary / 160;

    for ($i = 0; $i < $rows; $i++) {

        if (empty($date[$i]) || empty($start_time[$i]) || empty($end_time[$i])) {
            continue;
        }

        $start = new DateTime($date[$i] . ' ' . $start_time[$i]);
        $end = new DateTime($date[$i] . ' ' . $end_time[$i]);

        if ($end <= $start) {
            $end->modify('+1 day');
        }

        $interval = $start->diff($end);
        $hours = $interval->h + ($interval->i / 60);

        $extra_factor = 0.0;
        $extra_labels = [];

        if ($start->format('N') == 7) {
            $extra_factor += 0.50;
            $extra_labels[] = "+50% Sunday";
        }

        if ($start->format('H:i') >= "18:00") {
            $extra_factor += 0.25;
            $extra_labels[] = "+25% Night Shift";
        }

        $effective_rate = $hourly_rate * (1 + $extra_factor);
        $total = $effective_rate * $hours;

        $overtime_details[] = [
            "date" => $date[$i],
            "hours" => $hours,
            "base_rate" => $hourly_rate,
            "extra_labels" => $extra_labels,
            "effective_rate" => $effective_rate,
            "total" => $total
        ];

        $total_overtime_payment += $total;
    }
}

// Grand total
$grand_total_salary = $final_base_net + $total_overtime_payment;

?>