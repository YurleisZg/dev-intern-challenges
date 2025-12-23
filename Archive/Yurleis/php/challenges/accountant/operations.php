<?php
session_start();

require './state.php';
require './overtime_rules.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    unset($_SESSION['salary_state']);
}

// Load current state from session (or default)
$state = load_state_from_session();
$action = $_POST['action'] ?? '';

/**
 * Process the incoming POST data and update state accordingly.
 *
 * This function:
 *  - Updates base salary and rows count.
 *  - Handles "add_overtime" / "remove_overtime" actions.
 *  - Validates base salary range.
 *  - Calculates base salary breakdown (tax, health, bonus).
 *  - Calculates overtime details and totals.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    function calculate_tax(float $base_salary): float
    {
        if ($base_salary < 1000) {
            return 0;
        } elseif ($base_salary <= 2000) {
            return $base_salary * 0.10;
        }
        return $base_salary * 0.20;
    }

    function calculate_health_insurance(float $base_salary): float
    {
        return $base_salary * 0.05;
    }

    function calculate_bonus(float $base_salary): int
    {
        if ($base_salary > 0) {
            return rand(100, 500);
        }
        return 0;
    }


    // Update base salary if present in POST
    if (isset($_POST['base_salary']) && $_POST['base_salary'] !== '') {
        $state['base_salary'] = (float) $_POST['base_salary'];
    }

    // Update number of rows and overtime arrays
    if (isset($_POST['rows'])) {
        $state['rows'] = (int) $_POST['rows'];
        if ($state['rows'] < 0) {
            $state['rows'] = 0;
        }
    }

    $state['date'] = $_POST['date_overtime'] ?? $state['date'];
    $state['start_time'] = $_POST['start_time_overtime'] ?? $state['start_time'];
    $state['end_time'] = $_POST['end_time_overtime'] ?? $state['end_time'];

    // Handle add/remove overtime rows
    if ($action === 'add_overtime') {
        $state['rows']++;
    } elseif ($action === 'remove_overtime') {
        $state['rows']--;
        if ($state['rows'] < 0) {
            $state['rows'] = 0;
        }
        // Trim arrays to the new size
        $state['date'] = array_slice($state['date'], 0, $state['rows']);
        $state['start_time'] = array_slice($state['start_time'], 0, $state['rows']);
        $state['end_time'] = array_slice($state['end_time'], 0, $state['rows']);
    }

    // Clear previous errors on each POST
    $state['errors'] = [];

    /**
     * IMPORTANT:
     * We recalculate results when:
     *  - action = "calculate"
     *  - action = "remove_overtime"
     *
     * This way, when the user removes an overtime row, the result table
     * is updated immediately and the removed row disappears from the results.
     *
     * For "add_overtime" we keep previous results and do NOT recalculate
     * until the user explicitly clicks "Calculate salary".
     */
    if ($action === 'calculate' || $action === 'remove_overtime') {

        $bs = $state['base_salary'];

        // Validate base salary range (server-side)
        if ($bs < 1 || $bs > 9999999) {
            $state['errors']['base_salary'] = 'Base salary must be between 1 and 9,999,999.';
            $state['has_results'] = false;
            $state['tax'] = 0;
            $state['health_insurance'] = 0;
            $state['bonus'] = 0;
            $state['final_base_net'] = 0;
            $state['overtime_details'] = [];
            $state['total_overtime_payment'] = 0;
            $state['grand_total_salary'] = 0;

        } else {
            // --- Base salary calculations ---
            $state['tax'] = calculate_tax($bs);
            $state['health_insurance'] = calculate_health_insurance($bs);
            $state['bonus'] = calculate_bonus($bs);
            $state['final_base_net'] = $bs - $state['tax'] - $state['health_insurance'] + $state['bonus'];

            // --- Overtime calculations ---
            $state['overtime_details'] = [];
            $state['total_overtime_payment'] = 0.0;

            if ($state['rows'] > 0) {
                // Simplified assumption: 160 hours/month
                $hourly_rate = $bs / 160;

                for ($i = 0; $i < $state['rows']; $i++) {

                    $d = $state['date'][$i] ?? '';
                    $st = $state['start_time'][$i] ?? '';
                    $et = $state['end_time'][$i] ?? '';

                    // Skip incomplete rows
                    if ($d === '' || $st === '' || $et === '') {
                        continue;
                    }

                    $startDT = new DateTime($d . ' ' . $st);
                    $endDT = new DateTime($d . ' ' . $et);

                    // If the shift crosses midnight, move end to the next day
                    if ($endDT <= $startDT) {
                        $endDT->modify('+1 day');
                    }

                    // Apply overtime rules to get the distribution of hours
                    $hoursData = get_overtime_hours_by_rules($d, $st, $et);
                    $totalShift = $hoursData['total_hours'];
                    $hours = $hoursData['overtime_hours'];
                    $nightHours = $hoursData['night_hours'];
                    $dayHours = $hoursData['day_hours'];
                    $sundayHours = $hoursData['sunday_hours'];

                    // If there is no overtime according to the rules, skip the shift
                    if ($hours <= 0) {
                        continue;
                    }

                    $extra_labels = [];


                    // 2) Base overtime pay and premiums
                    $basePay = $hourly_rate * $hours; 
                    $extraPay = 0.0;               
                    $extraSunday = 0.0;
                    $extraNight = 0.0;

                    // +50% Sunday premium for overtime hours that happen on Sunday
                    if ($sundayHours > 0) {
                        $extraSunday = 0.50 * $hourly_rate * $sundayHours;
                        $extraPay += $extraSunday;
                        $extra_labels[] = sprintf(
                            "+50%% Sunday / holiday premium over %.2f Sunday overtime hours\n",
                            $sundayHours
                        );
                    }

                    // +25% night premium for night overtime (18:00–06:00)
                    if ($nightHours > 0) {
                        $extraNight = 0.25 * $hourly_rate * $nightHours;
                        $extraPay += $extraNight;
                        $extra_labels[] = sprintf(
                            "+25%% Night shift premium over %.2f night overtime hours\n",
                            $nightHours
                        );
                    }

                    $total = $basePay + $extraPay;
                    $effective_rate = $total / $hours;

                    $state['overtime_details'][] = [
                        'date' => $d,
                        'hours' => $hours,
                        'base_rate' => $hourly_rate,
                        'extra_labels' => $extra_labels,
                        'effective_rate' => $effective_rate,
                        'total' => $total,
                        'base_pay' => $basePay,
                        'extra_pay' => $extraPay,
                        'extra_sunday' => $extraSunday,
                        'extra_night' => $extraNight,
                    ];

                    $state['total_overtime_payment'] += $total;
                }
            }

            // Final net salary including overtime
            $state['grand_total_salary'] = $state['final_base_net'] + $state['total_overtime_payment'];
            $state['has_results'] = true;
        }
    }
}
// Persist state into session for the next request
save_state_to_session($state);

/**
 * Export simple variables for the view (index.php).
 * This keeps index.php clean and avoids touching the state array directly.
 */
$base_salary = $state['base_salary'];
$rows = $state['rows'];
$date = $state['date'];
$start_time = $state['start_time'];
$end_time = $state['end_time'];
$errors = $state['errors'];
$has_results = $state['has_results'];
$tax = $state['tax'];
$health_insurance = $state['health_insurance'];
$bonus = $state['bonus'];
$final_base_net = $state['final_base_net'];
$overtime_details = $state['overtime_details'];
$total_overtime_payment = $state['total_overtime_payment'];
$grand_total_salary = $state['grand_total_salary'];

// Keep $action available in the view if needed
$action = $_POST['action'] ?? '';
