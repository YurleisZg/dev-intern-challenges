<?php

/**
 * Returns the default calculator state.
 * This is the "shape" of all data used by the salary & overtime calculator.
 */
function get_default_state(): array
{
    return [
        'base_salary'            => 0.0,
        'rows'                   => 0,
        'date'                   => [],
        'start_time'             => [],
        'end_time'               => [],
        'errors'                 => [],
        'has_results'            => false,
        'tax'                    => 0.0,
        'health_insurance'       => 0.0,
        'bonus'                  => 0.0,
        'final_base_net'         => 0.0,
        'overtime_details'       => [],
        'total_overtime_payment' => 0.0,
        'grand_total_salary'     => 0.0,
    ];
}

/**
 * Loads the calculator state from session or returns the default state.
 */
function load_state_from_session(): array
{
    if (!isset($_SESSION['salary_state']) || !is_array($_SESSION['salary_state'])) {
        return get_default_state();
    }
    return $_SESSION['salary_state'];
}

/**
 * Persists the calculator state into the session.
 */
function save_state_to_session(array $state): void
{
    $_SESSION['salary_state'] = $state;
}
