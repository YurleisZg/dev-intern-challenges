<?php

namespace App\Features\Elkin\SalaryCalculator\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryRecordDetail extends Model
{
    protected $table = 'elkin_salary_records_details';
    protected $primaryKey = 'detail_id';

    protected $fillable = [
        'record_id',
        'shift_date',
        'start_time',
        'end_time',
    ];

    public $timestamps = false;

    // Relación con SalaryRecord
    public function salaryRecord()
    {
        return $this->belongsTo(SalaryRecord::class, 'record_id', 'record_id');
    }
}
