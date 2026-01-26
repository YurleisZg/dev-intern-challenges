<?php

namespace App\Features\Elkin\SalaryCalculator\Domain;

class SalaryCalculation
{
    public function __construct(
        public float $gross_salary,
        public float $tax,
        public float $health,
        public float $bonus,
        public float $base_salary,
        public float $hourly_rate,
        public array $overtime_data,
        public float $total_overtime,
        public float $grand_total
    ) {}

    public function toArray(): array
    {
        return [
            'gross_salary' => $this->gross_salary,
            'tax' => $this->tax,
            'health' => $this->health,
            'bonus' => $this->bonus,
            'base_salary' => $this->base_salary,
            'hourly_rate' => $this->hourly_rate,
            'overtime_data' => $this->overtime_data,
            'total_overtime' => $this->total_overtime,
            'grand_total' => $this->grand_total,
        ];
    }

    public function getSummary(): array
    {
        return [
            'gross' => $this->gross_salary,
            'deductions' => $this->tax + $this->health,
            'net_base' => $this->base_salary,
            'overtime' => $this->total_overtime,
            'total' => $this->grand_total,
        ];
    }
}
