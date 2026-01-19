<?php

/**
 * Salary Calculator Feature Configuration
 * 
 * This file contains configuration constants for the Salary Calculator feature.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Tax Configuration
    |--------------------------------------------------------------------------
    */
    'tax' => [
        'low_threshold' => 1000,      // Below this amount, no tax
        'low_rate' => 0,              // 0% tax for low amounts
        'mid_threshold' => 2000,      // Between low_threshold and mid_threshold
        'mid_rate' => 0.10,           // 10% tax for mid amounts
        'high_rate' => 0.20,          // 20% tax for amounts above mid_threshold
    ],

    /*
    |--------------------------------------------------------------------------
    | Health Deduction Configuration
    |--------------------------------------------------------------------------
    */
    'health' => [
        'rate' => 0.05,               // 5% health deduction from gross salary
    ],

    /*
    |--------------------------------------------------------------------------
    | Bonus Configuration
    |--------------------------------------------------------------------------
    */
    'bonus' => [
        'min' => 100,                 // Minimum bonus
        'max' => 500,                 // Maximum bonus
    ],

    /*
    |--------------------------------------------------------------------------
    | Overtime Configuration
    |--------------------------------------------------------------------------
    */
    'overtime' => [
        'standard_hours_per_month' => 160,      // Standard hours per month
        'night_shift_start' => '18:00',         // Night shift starts at 6 PM
        'night_shift_bonus' => 0.25,            // 25% bonus for night shift
        'sunday_bonus' => 0.50,                 // 50% bonus for Sunday work
    ],

    /*
    |--------------------------------------------------------------------------
    | Export Configuration
    |--------------------------------------------------------------------------
    */
    'export' => [
        'enabled' => true,
        'formats' => ['csv', 'pdf'],            // Supported export formats
    ],

    /*
    |--------------------------------------------------------------------------
    | Record Retention
    |--------------------------------------------------------------------------
    */
    'retention' => [
        'days' => 365,                // Keep records for 1 year
        'auto_cleanup' => false,      // Automatically delete old records
    ],
];
