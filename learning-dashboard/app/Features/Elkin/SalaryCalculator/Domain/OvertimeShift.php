<?php

namespace App\Features\Elkin\SalaryCalculator\Domain;

use Carbon\Carbon;

class OvertimeShift
{
    public function __construct(
        public string $date,
        public string $start_time,
        public string $end_time
    ) {}

    public function getDate(): Carbon
    {
        return Carbon::parse($this->date);
    }

    public function isSunday(): bool
    {
        return $this->getDate()->isSunday();
    }

    public function isNightShift(): bool
    {
        $nightStart = strtotime('18:00');
        $start = strtotime($this->start_time);

        return $start >= $nightStart;
    }

    public function getHours(): float
    {
        $start = strtotime($this->start_time);
        $end = strtotime($this->end_time);

        if ($end < $start) {
            $end += 86400; // Add 24 hours if end is next day
        }

        return ($end - $start) / 3600;
    }

    public function toArray(): array
    {
        return [
            'date' => $this->date,
            'start' => $this->start_time,
            'end' => $this->end_time,
            'hours' => $this->getHours(),
            'is_sunday' => $this->isSunday(),
            'is_night' => $this->isNightShift(),
        ];
    }
}
