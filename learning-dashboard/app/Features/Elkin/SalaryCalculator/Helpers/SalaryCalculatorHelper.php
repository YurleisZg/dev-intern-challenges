<?php

namespace App\Features\Elkin\SalaryCalculator\Helpers;

use Carbon\Carbon;

class SalaryCalculatorHelper
{
    /**
     * Format salary as currency
     */
    public static function formatCurrency($amount): string
    {
        return '$' . number_format($amount, 2);
    }

    /**
     * Format hours with proper precision
     */
    public static function formatHours($hours): string
    {
        return number_format($hours, 2) . ' hrs';
    }

    /**
     * Get bonus emoji based on amount
     */
    public static function getBonusEmoji($amount): string
    {
        if ($amount >= 400) {
            return '🏆';
        } elseif ($amount >= 300) {
            return '⭐';
        } elseif ($amount >= 200) {
            return '👍';
        }
        return '💰';
    }

    /**
     * Get status badge color
     */
    public static function getStatusBadgeClass($status): string
    {
        return match ($status) {
            'completed' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'failed' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get shift type label
     */
    public static function getShiftTypeLabel($isNight, $isSunday): string
    {
        $labels = [];

        if ($isSunday) {
            $labels[] = 'Sunday +50%';
        }

        if ($isNight) {
            $labels[] = 'Night +25%';
        }

        return implode(' | ', $labels) ?: 'Regular';
    }

    /**
     * Get shift type badge classes
     */
    public static function getShiftTypeBadgeClasses($isNight, $isSunday): array
    {
        $classes = [];

        if ($isSunday) {
            $classes[] = 'bg-red-100 text-red-700';
        }

        if ($isNight) {
            $classes[] = 'bg-blue-100 text-blue-700';
        }

        return $classes;
    }

    /**
     * Calculate percentage change
     */
    public static function calculatePercentageChange($original, $new): float
    {
        if ($original == 0) {
            return 0;
        }

        return (($new - $original) / $original) * 100;
    }

    /**
     * Format date for display
     */
    public static function formatShiftDate($date): string
    {
        return Carbon::parse($date)->format('D, M j, Y');
    }

    /**
     * Get date range label
     */
    public static function getDateRangeLabel($startDate, $endDate): string
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        if ($start->isSameDay($end)) {
            return $start->format('M j, Y');
        } elseif ($start->isSameMonth($end) && $start->isSameYear($end)) {
            return $start->format('M j') . ' - ' . $end->format('j, Y');
        } else {
            return $start->format('M j, Y') . ' - ' . $end->format('M j, Y');
        }
    }

    /**
     * Validate salary amount
     */
    public static function isValidSalary($amount): bool
    {
        return is_numeric($amount) && $amount > 0;
    }

    /**
     * Validate time format
     */
    public static function isValidTime($time): bool
    {
        return preg_match('/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/', $time);
    }

    /**
     * Validate date format
     */
    public static function isValidDate($date): bool
    {
        return strtotime($date) !== false;
    }
}
