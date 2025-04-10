<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\TestAttempt;
use App\Models\Question;
use App\Models\Option;

class TestAnswer extends Model
{
    use HasFactory;
    
    protected $fillable = ['test_attempt_id', 'question_id', 'selected_option_id'];
    
    public function testAttempt()
    {
        return $this->belongsTo(TestAttempt::class);
    }
    
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    
    public function selectedOption()
    {
        return $this->belongsTo(Option::class, 'selected_option_id');
    }
}
