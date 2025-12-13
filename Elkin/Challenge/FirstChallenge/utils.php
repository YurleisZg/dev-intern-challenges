<?php

// Función para calcular impuestos
function calculateTax($grossSalary) {
    if ($grossSalary < 1000) {
        return 0;
    } elseif ($grossSalary <= 2000) {
        return $grossSalary * 0.10;
    }

    return $grossSalary * 0.20;
}

// Función para calcular descuento de salud
function calculateHealth($grossSalary) {
    return $grossSalary * 0.05;
}

// Función para generar bono aleatorio
function generateBonus() {
    return rand(100, 500);
}

// Función para calcular tarifa por hora
function calculateHourlyRate($monthlySalary) {
    return $monthlySalary / 160;
}

// Función para verificar si es domingo
function isSunday($date) {
    return date('w', strtotime($date)) == 0;
}

// Función para verificar si es turno nocturno (después de 6:00 PM)
function isNightShift($startTime, $endTime) {
    $nightStart = strtotime('18:00');
    $start = strtotime($startTime);
    $end = strtotime($endTime);

    if ($end < $start) {
        $end += 86400;
    }

    return $start >= $nightStart || $end > $nightStart;
}

// Función para calcular horas trabajadas
function calculateHours($startTime, $endTime) {
    $start = strtotime($startTime);
    $end = strtotime($endTime);

    if ($end < $start) {
        $end += 86400;
    }

    $diff = $end - $start;
    return $diff / 3600;
}

/**
 * Calcula los valores finales de un salario dado el sueldo base y un conjunto de turnos extra.
 * $overtimeRecords debe ser un arreglo de arrays con las llaves: date, start, end.
 */
function computeSalaryResult(float $grossSalary, array $overtimeRecords): array {
    $tax = calculateTax($grossSalary);
    $health = calculateHealth($grossSalary);
    $bonus = generateBonus();
    $baseSalary = $grossSalary - $tax - $health + $bonus;

    $hourlyRate = calculateHourlyRate($grossSalary);

    $overtimeData = [];
    $totalOvertime = 0;

    foreach ($overtimeRecords as $record) {
        $date = $record['date'] ?? null;
        $startTime = $record['start'] ?? null;
        $endTime = $record['end'] ?? null;

        if (empty($date) || empty($startTime) || empty($endTime)) {
            continue;
        }

        $hours = calculateHours($startTime, $endTime);
        $baseRate = $hourlyRate;
        $sundayBonus = 0;
        $nightBonus = 0;

        if (isSunday($date)) {
            $sundayBonus = $hourlyRate * 0.50;
        }

        if (isNightShift($startTime, $endTime)) {
            $nightBonus = $hourlyRate * 0.25;
        }

        $totalRate = $baseRate + $sundayBonus + $nightBonus;
        $shiftTotal = $hours * $totalRate;

        $overtimeData[] = [
            'date' => $date,
            'start' => $startTime,
            'end' => $endTime,
            'hours' => $hours,
            'base_rate' => $baseRate,
            'sunday_bonus' => $sundayBonus,
            'night_bonus' => $nightBonus,
            'total_rate' => $totalRate,
            'shift_total' => $shiftTotal
        ];

        $totalOvertime += $shiftTotal;
    }

    return [
        'gross_salary' => $grossSalary,
        'tax' => $tax,
        'health' => $health,
        'bonus' => $bonus,
        'base_salary' => $baseSalary,
        'hourly_rate' => $hourlyRate,
        'overtime_data' => $overtimeData,
        'total_overtime' => $totalOvertime,
        'grand_total' => $baseSalary + $totalOvertime
    ];
}


function handleOvertimeRows() {
    if (!isset($_SESSION['overtime_rows'])) {
        $_SESSION['overtime_rows'] = 1;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_row'])) {
            $_SESSION['overtime_rows']++;
        }

        if (isset($_POST['remove_row'])) {
            $indexToRemove = intval($_POST['remove_row']);

            if (isset($_POST['overtime_date']) && is_array($_POST['overtime_date'])) {
                $dates = $_POST['overtime_date'];
                $starts = $_POST['overtime_start'] ?? [];
                $ends = $_POST['overtime_end'] ?? [];

                if (array_key_exists($indexToRemove, $dates)) {
                    unset($dates[$indexToRemove]);
                }
                if (array_key_exists($indexToRemove, $starts)) {
                    unset($starts[$indexToRemove]);
                }
                if (array_key_exists($indexToRemove, $ends)) {
                    unset($ends[$indexToRemove]);
                }

                $_POST['overtime_date'] = array_values($dates);
                $_POST['overtime_start'] = array_values($starts);
                $_POST['overtime_end'] = array_values($ends);
            }

            if ($_SESSION['overtime_rows'] > 1) {
                $_SESSION['overtime_rows']--;
            }
        }
    }

    return $_SESSION['overtime_rows'];
}


function processSalaryForm($overtimeRows) {
    $result = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calculate'])) {
        $grossSalary = (float) ($_POST['gross_salary'] ?? 0);

        $overtimeRecords = [];
        if (isset($_POST['overtime_date']) && is_array($_POST['overtime_date'])) {
            foreach ($_POST['overtime_date'] as $index => $date) {
                $overtimeRecords[] = [
                    'date' => $date,
                    'start' => $_POST['overtime_start'][$index] ?? null,
                    'end' => $_POST['overtime_end'][$index] ?? null,
                ];
            }
        }

        $result = computeSalaryResult($grossSalary, $overtimeRecords);
    }

    $formData = [
        'gross_salary' => $_POST['gross_salary'] ?? '',
        'overtime_date' => $_POST['overtime_date'] ?? array_fill(0, $overtimeRows, ''),
        'overtime_start' => $_POST['overtime_start'] ?? array_fill(0, $overtimeRows, ''),
        'overtime_end' => $_POST['overtime_end'] ?? array_fill(0, $overtimeRows, '')
    ];

    return [$formData, $result];
}

