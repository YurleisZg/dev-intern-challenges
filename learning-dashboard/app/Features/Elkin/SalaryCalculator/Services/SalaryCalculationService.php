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
     * Get multiplier for a given hour based on day and time
     * Monday-Friday: Before 18:00 = 1.0x, After 18:00 = 1.25x
     * Saturday: Before 13:00 = 1.0x, After 13:00 = 1.25x
     * Sunday: Before 18:00 = 1.50x, After 18:00 = 1.75x
     */
    public static function getHourMultiplier($date, $hour)
    {
        $dayOfWeek = date('w', strtotime($date)); // 0=Sunday, 6=Saturday
        
        if ($dayOfWeek == 0) { // Sunday
            return $hour >= 18 ? 1.75 : 1.50;
        } elseif ($dayOfWeek == 6) { // Saturday
            return $hour >= 13 ? 1.25 : 1.0;
        } else { // Monday-Friday
            return $hour >= 18 ? 1.25 : 1.0;
        }
    }

    /**
     * Process overtime with fragmented hour calculation
     * Each hour is calculated separately based on time of day
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

            // Parse times
            $start = strtotime("$date $startTime");
            $end = strtotime("$date $endTime");
            
            // Handle overnight shifts
            if ($end < $start) {
                $end += 86400;
            }

            // Calculate total hours
            $totalHours = ($end - $start) / 3600;

            // Skip invalid or zero-length shifts to prevent divide-by-zero later
            if ($totalHours <= 0 || $hourlyRate <= 0) {
                continue;
            }

            // Fragment calculation by hour
            $segments = [];
            $currentTime = $start;
            $shiftTotal = 0;

            while ($currentTime < $end) {
                $currentHour = (int)date('H', $currentTime);
                $nextHour = min($currentTime + 3600, $end);
                $hoursInSegment = ($nextHour - $currentTime) / 3600;
                
                $multiplier = self::getHourMultiplier($date, $currentHour);
                $segmentRate = $hourlyRate * $multiplier;
                $segmentTotal = $hoursInSegment * $segmentRate;
                
                $segments[] = [
                    'hour' => $currentHour,
                    'hours' => $hoursInSegment,
                    'multiplier' => $multiplier,
                    'rate' => $segmentRate,
                    'total' => $segmentTotal
                ];
                
                $shiftTotal += $segmentTotal;
                $currentTime = $nextHour;
            }

            // Calculate average multiplier for display
            $avgMultiplier = $shiftTotal / ($totalHours * $hourlyRate);

            $overtimeData[] = [
                'date' => $date,
                'start' => $startTime,
                'end' => $endTime,
                'total_hours' => $totalHours,
                'base_rate' => $hourlyRate,
                'avg_multiplier' => $avgMultiplier,
                'shift_total' => $shiftTotal,
                'segments' => $segments
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
