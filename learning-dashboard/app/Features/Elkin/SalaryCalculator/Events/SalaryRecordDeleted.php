<?php

namespace App\Features\Elkin\SalaryCalculator\Events;

use App\Features\Elkin\SalaryCalculator\Models\SalaryRecord;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SalaryRecordDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public SalaryRecord $record
    ) {}
}
