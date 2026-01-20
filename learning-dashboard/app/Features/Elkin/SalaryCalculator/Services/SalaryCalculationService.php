<?php

namespace App\Features\Elkin\SalaryCalculator\Services;

use App\Features\Elkin\SalaryCalculator\Domain\SalaryCalculation;
use App\Features\Elkin\SalaryCalculator\Domain\OvertimeShift;

class SalaryCalculationService
{
    /**
     * Calculate tax based on gross salary
     */
    public static function calculateTax($grossSalary)
    {
        if ($grossSalary < 1000) {
            return 0;
        } elseif ($grossSalary <= 2000) {
            return $grossSalary * 0.10;
        }

        return $grossSalary * 0.20;
    }

    /**
     * Calculate health deduction (5% of gross salary)
     */
    public static function calculateHealth($grossSalary)
    {
        return $grossSalary * 0.05;
    }

    /**
     * Generate random bonus between $100-$500
     */
    public static function generateBonus()
    {
        return rand(100, 500);
    }

    /**
     * Calculate hourly rate (monthly salary / 160 hours)
     */
    public static function calculateHourlyRate($monthlySalary)
    {
        return $monthlySalary / 160;
    }

    /**
     * Check if date is Sunday
     */
    public static function isSunday($date)
    {
        return date('w', strtotime($date)) == 0;
    }

    /**
     * Check if shift is night shift (after 6:00 PM)
     */
    public static function isNightShift($startTime, $endTime)
    {
        $nightStart = strtotime('18:00');
        $start = strtotime($startTime);
        $end = strtotime($endTime);

        if ($end < $start) {
            $end += 86400; // Add 24 hours if end is next day
        }

        return $start >= $nightStart || $end > $nightStart;
    }

    /**
     * Check if shift is on Saturday after 1:00 PM
     */
    public static function isSaturdayAfternoon($date, $startTime)
    {
        $isSaturday = date('w', strtotime($date)) == 6; // 6 = Saturday
        $afternoonStart = strtotime('13:00');
        $start = strtotime($startTime);

        return $isSaturday && $start >= $afternoonStart;
    }

    /**
     * Calculate hours worked between two times
     */
    public static function calculateHours($startTime, $endTime)
    {
        $start = strtotime($startTime);
        $end = strtotime($endTime);

        if ($end < $start) {
            $end += 86400; // Add 24 hours if end is next day
        }

        $diff = $end - $start;
        return $diff / 3600;
    }

    /**
     * Process overtime data and calculate totals
     */
    public static function processOvertime($overtimeDates, $overtimeTimes, $hourlyRate)
    {
        $overtimeData = [];
        $totalOvertime = 0;

        if (!is_array($overtimeDates) || empty($overtimeDates)) {
            return [$overtimeData, $totalOvertime];
        }

        foreach ($overtimeDates as $index => $date) {
            $startTime = $overtimeTimes[$index]['start'] ?? null;
            $endTime = $overtimeTimes[$index]['end'] ?? null;

            if (empty($date) || empty($startTime) || empty($endTime)) {
                continue;
            }

            $hours = self::calculateHours($startTime, $endTime);
            $baseRate = $hourlyRate;
            $sundayBonus = 0;
            $nightBonus = 0;
            $saturdayAfternoonBonus = 0;

            if (self::isSunday($date)) {
                $sundayBonus = $hourlyRate * 0.50; // 50% Sunday bonus
            }

            if (self::isSaturdayAfternoon($date, $startTime)) {
                $saturdayAfternoonBonus = $hourlyRate * 0.25; // 25% Saturday afternoon bonus
            }

            if (self::isNightShift($startTime, $endTime)) {
                $nightBonus = $hourlyRate * 0.25; // 25% night bonus
            }

            $totalRate = $baseRate + $sundayBonus + $saturdayAfternoonBonus + $nightBonus;
            $shiftTotal = $hours * $totalRate;

            $overtimeData[] = [
                'date' => $date,
                'start' => $startTime,
                'end' => $endTime,
                'hours' => $hours,
                'base_rate' => $baseRate,
                'sunday_bonus' => $sundayBonus,
                'saturday_afternoon_bonus' => $saturdayAfternoonBonus,
                'night_bonus' => $nightBonus,
                'total_rate' => $totalRate,
                'shift_total' => $shiftTotal
            ];

            $totalOvertime += $shiftTotal;
        }

        return [$overtimeData, $totalOvertime];
    }

    /**
     * Calculate complete salary summary
     */
    public static function calculateSalary($grossSalary, $overtimeDates, $overtimeTimes)
    {
        $tax = self::calculateTax($grossSalary);
        $health = self::calculateHealth($grossSalary);
        $bonus = self::generateBonus();
        $baseSalary = $grossSalary - $tax - $health + $bonus;
        $hourlyRate = self::calculateHourlyRate($grossSalary);

        [$overtimeData, $totalOvertime] = self::processOvertime($overtimeDates, $overtimeTimes, $hourlyRate);

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
}
