<?php

namespace App\Features\Yurleis\SalaryCalculator\Services;

use Carbon\CarbonImmutable;

class SalaryCalculatorService
{
    /**
     * @param float $gross
     * @param array<int, array{date:string,start_time:string,end_time:string}> $shifts
     */
    public function calculate(float $gross, array $shifts): array
    {
        $hourlyRate = $gross / 160.0;

        // Tax: <1000 0%, 1000-2000 10%, >2000 20%
        $taxRate = $gross < 1000 ? 0 : ($gross <= 2000 ? 0.10 : 0.20);
        $tax = $gross * $taxRate;

        // Health: 5%
        $health = $gross * 0.05;

        // Bonus: fixed $300
        $bonus = 300.0;

        $baseNet = $gross - $tax - $health + $bonus;

        $rows = [];
        $overtimeTotal = 0.0;

        foreach ($shifts as $s) {
            $row = $this->calcShift($s['date'], $s['start_time'], $s['end_time'], $hourlyRate);
            $rows[] = $row;
            $overtimeTotal += $row['total'];
        }

        return [
            'hourly_rate' => $hourlyRate,

            'gross_salary' => $this->floor2($gross),
            'tax' => $this->floor2($tax),
            'health' => $this->floor2($health),
            'bonus' => $this->floor2($bonus),
            'base_net' => $this->floor2($baseNet),

            'overtime_total' => $this->floor2($overtimeTotal),
            'grand_total' => $this->floor2($baseNet + $overtimeTotal),

            'shift_rows' => $rows,
        ];
    }

    private function calcShift(string $date, string $start, string $end, float $hourlyRate): array
    {
        $startAt = CarbonImmutable::parse("$date $start");
        $endAt   = CarbonImmutable::parse("$date $end");

        // If it crosses midnight
        if ($endAt <= $startAt) {
            $endAt = $endAt->addDay();
        }

        $overtimeMinutes = 0;
        $nightMinutes = 0;
        $sundayMinutes = 0;

        for ($t = $startAt; $t < $endAt; $t = $t->addMinute()) {
            if ($this->isOvertimeMinute($t)) {
                $overtimeMinutes++;

                if ($this->isNightMinute($t)) $nightMinutes++;
                if ($t->isSunday()) $sundayMinutes++;
            }
        }

        $isSunday = $sundayMinutes > 0;

        //base overtime + sunday + night
        $multiplier = 1.0;
        if ($isSunday) $multiplier += 0.50;
        if ($nightMinutes > 0) $multiplier += 0.25;

        $hours = $overtimeMinutes / 60.0;
        $total = $hours * $hourlyRate * $multiplier;

        return [
            'date' => $date,
            'start_time' => $start,
            'end_time' => $end,

            'overtime_minutes' => $overtimeMinutes,
            'night_overtime_minutes' => $nightMinutes,
            'is_sunday' => $isSunday,

            'hourly_rate' => $hourlyRate,
            'multiplier' => $multiplier,
            'total' => $this->floor2($total),
        ];
    }

    /**
     * Rules:
     * Mon–Fri: overtime 18:00–06:00
     * Sat: normal 06:00–13:00, overtime otherwise
     * Sun: everything overtime
     */
    private function isOvertimeMinute(CarbonImmutable $t): bool
    {
        if ($t->isSunday()) return true;

        $hhmm = $t->format('H:i');

        if ($t->isSaturday()) {
            return ($hhmm < '06:00') || ($hhmm >= '13:00');
        }

        // Mon-Fri
        return ($hhmm >= '18:00') || ($hhmm < '06:00');
    }

    // Night window 18:00–06:00
    private function isNightMinute(CarbonImmutable $t): bool
    {
        $hhmm = $t->format('H:i');
        return ($hhmm >= '18:00') || ($hhmm < '06:00');
    }

    private function floor2(float $v): float
    {
        return floor($v * 100) / 100;
    }
}
