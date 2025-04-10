<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Question;

class Option extends Model
{
    use HasFactory;
    
    protected $fillable = ['question_id', 'option_text', 'is_correct'];
    
    public function question()
    {
        return $this->belongsTo(Question::class);
    }//
}
