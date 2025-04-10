<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Option;

class Question extends Model
{
    use HasFactory;
    
    protected $fillable = ['question_text'];
    
    public function options()
    {
        return $this->hasMany(Option::class);
    }
    
    public function correctOption()
    {
        return $this->options()->where('is_correct', true)->first();
    }
}
