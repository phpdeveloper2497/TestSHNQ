<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\TestAttempt;
use App\Models\TestAnswer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function start()
    {
        $questions = Question::inRandomOrder()->take(50)->get();
        
        // Load and randomize options for each question
        foreach ($questions as $question) {
            $options = $question->options()->get()->shuffle()->values();
            $question->setRelation('options', $options);
        }
        
        $testAttempt = TestAttempt::create([
            'started_at' => now(),
        ]);
        
        return view('test.start', compact('questions', 'testAttempt'));
    }
    
    public function submit(Request $request, TestAttempt $testAttempt)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|exists:options,id'
        ], [
            'answers.required' => 'Please answer at least one question',
            'answers.array' => 'Invalid answer format',
            'answers.*.required' => 'Please select an answer for each question'
        ]);
        
        foreach ($request->answers as $questionId => $optionId) {
            TestAnswer::create([
                'test_attempt_id' => $testAttempt->id,
                'question_id' => $questionId,
                'selected_option_id' => $optionId
            ]);
        }
        
        $finishedAt = now();
        $startedAt = Carbon::parse($testAttempt->started_at);
        $totalSeconds = $startedAt->diffInSeconds($finishedAt);
        $minutes = (int)floor($totalSeconds / 60);
        $seconds = (int)($totalSeconds % 60);
        
        $testAttempt->update([
            'finished_at' => $finishedAt,
            'score' => $this->calculateScore($testAttempt),
            'duration_minutes' => $minutes,
            'duration_seconds' => $seconds
        ]);
        
        // Log the values for debugging
        \Log::info('Test duration saved:', [
            'minutes' => $minutes,
            'seconds' => $seconds,
            'test_id' => $testAttempt->id
        ]);
        
        return redirect()->route('test.result', $testAttempt);
    }
    
    public function result(TestAttempt $testAttempt)
    {
        $testAttempt->load(['answers.question.options', 'answers.selectedOption']);
        $minutes = $testAttempt->duration_minutes;
        $seconds = $testAttempt->duration_seconds;
        
        return view('test.result', compact('testAttempt', 'minutes', 'seconds'));
    }
    
    public function history()
    {
        $attempts = TestAttempt::with('answers')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($attempt) {
                Log::info('Test attempt duration:', [
                    'test_id' => $attempt->id,
                    'minutes' => $attempt->duration_minutes,
                    'seconds' => $attempt->duration_seconds
                ]);
                
                return [
                    'id' => $attempt->id,
                    'date' => $attempt->created_at->format('d.m.Y H:i'),
                    'time' => $attempt->created_at->format('H:i'),
                    'score' => $attempt->score,
                    'total_questions' => $attempt->answers->count(),
                    'minutes' => $attempt->duration_minutes ?? 0,
                    'seconds' => $attempt->duration_seconds ?? 0
                ];
            });
            
        return view('test.history', compact('attempts'));
    }

    private function calculateScore(TestAttempt $testAttempt)
    {
        $correctAnswers = 0;
        foreach ($testAttempt->answers as $answer) {
            if ($answer->selectedOption->is_correct) {
                $correctAnswers++;
            }
        }
        return $correctAnswers;
    }
}
