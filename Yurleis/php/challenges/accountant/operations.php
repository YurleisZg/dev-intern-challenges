<?php
$action = $_POST['action'] ?? '';


$rows = isset($_POST['rows']) ? (int)$_POST['rows'] : 1;
if ($rows < 1) {
    $rows = 1;
}
if ($action === 'add_row') {
    $rows++; 
}

$base_salary = isset($_POST['base_salary']) ? (float)$_POST['base_salary'] : 0.0;
$ot_dates  = $_POST['ot_date']  ?? [];
$ot_starts = $_POST['ot_start'] ?? [];
$ot_ends   = $_POST['ot_end']   ?? [];

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

if ($action === 'calculate' && $base_salary > 0) {

    $tax = calculate_tax($base_salary);
    $health_insurance = calculate_health_insurance($base_salary);
    $bonus = calculate_bonus($base_salary);
    $final_base_net = $base_salary - $tax - $health_insurance + $bonus;

    $hourly_rate = $base_salary / 160;

    for ($i = 0; $i < count($ot_dates); $i++) {
        $date  = trim($ot_dates[$i]  ?? '');
        $start = trim($ot_starts[$i] ?? '');
        $end   = trim($ot_ends[$i]   ?? '');

        if ($date === '' || $start === '' || $end === '') {
            continue; 
        }

    
        $start_dt = new DateTime("$date $start");
        $end_dt   = new DateTime("$date $end");

        if ($end_dt <= $start_dt) {
            $end_dt->modify('+1 day');
        }

        $interval = $start_dt->diff($end_dt);
        $hours = $interval->h + ($interval->i / 60);

        $extra_factor = 0.0;

        $day_of_week = (int)$start_dt->format('w');
        $is_sunday = ($day_of_week === 0);
        if ($is_sunday) {
            $extra_factor += 0.50; 
        }

        $time_string = $start_dt->format('H:i');
        $is_night = ($time_string >= '18:00');
        if ($is_night) {
            $extra_factor += 0.25;
        }

        $effective_rate = $hourly_rate * (1 + $extra_factor);
        $shift_pay = $hours * $effective_rate;
        $shift_pay_rounded = floor($shift_pay);

        $total_overtime_pay += $shift_pay_rounded;

        $overtime_rows[] = [
            'date'      => $date,
            'hours'     => $hours,
            'base_rate' => $hourly_rate,
            'is_sunday' => $is_sunday,
            'is_night'  => $is_night,
            'total'     => $shift_pay_rounded,
        ];
    }
    $grand_total = $final_base_net + $total_overtime_pay;
}
