<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\TestAnswer;

class TestAttempt extends Model
{
    use HasFactory;
    
    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime'
    ];
    
    protected $fillable = ['user_id', 'started_at', 'finished_at', 'score', 'duration_minutes', 'duration_seconds'];
    
    protected $dates = ['started_at', 'finished_at'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function answers()
    {
        return $this->hasMany(TestAnswer::class);
    }
}
