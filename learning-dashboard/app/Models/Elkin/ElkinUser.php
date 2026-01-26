<?php

namespace App\Models\Elkin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ElkinUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'elkin_users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null; // No hay updated_at en la tabla elkin_users

    // Relación con SalaryRecord
    public function salaryRecords()
    {
        return $this->hasMany(SalaryRecord::class, 'user_id', 'user_id');
    }
}
