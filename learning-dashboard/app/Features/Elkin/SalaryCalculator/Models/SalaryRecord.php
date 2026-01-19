<?php

namespace App\Features\Elkin\SalaryCalculator\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Elkin\ElkinUser;

class SalaryRecord extends Model
{
    protected $table = 'elkin_salary_records';
    protected $primaryKey = 'record_id';

    protected $fillable = [
        'user_id',
        'gross_salary_input',
        'status',
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Relación con ElkinUser
    public function user()
    {
        return $this->belongsTo(ElkinUser::class, 'user_id', 'id');
    }

    // Relación con SalaryRecordDetail
    public function details()
    {
        return $this->hasMany(SalaryRecordDetail::class, 'record_id', 'record_id');
    }
}
