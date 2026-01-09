<?php
namespace App\Models\Yurleis;

use Illuminate\Database\Eloquent\Model;

class OvertimeShift extends Model
{
    protected $fillable = [
        'salary_record_id',
        'date','start_time','end_time',
        'overtime_minutes','night_overtime_minutes','is_sunday',
        'hourly_rate','multiplier','total'
    ];

    public function record()
    {
        return $this->belongsTo(SalaryRecord::class, 'salary_record_id');
    }
}
