<?php

namespace App\Models\Yurleis;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ToggleGame extends Model
{
    use HasFactory;

    protected $table = 'yurleis_toggle_games';

    protected $fillable = [
        'yurleis_user_id',
        'stage',
        'status',
        'stage1_data',
        'stage2_data',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'yurleis_user_id');
    }
}