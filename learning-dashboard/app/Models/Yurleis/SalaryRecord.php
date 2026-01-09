<?php
namespace App\Models\Yurleis;

use Illuminate\Database\Eloquent\Model;

class SalaryRecord extends Model
{
    protected $fillable = [
        'yurleis_user_id','gross_salary',
        'tax','health','bonus','base_net',
        'overtime_total','grand_total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'yurleis_user_id');
    }

    public function shifts()
    {
        return $this->hasMany(OvertimeShift::class);
    }
}
