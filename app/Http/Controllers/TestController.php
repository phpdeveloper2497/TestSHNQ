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

        $questionIds = Question::select('id')
            ->distinct()
            ->pluck('id')
            ->toArray();

        $randomIds = collect($questionIds)
            ->shuffle() // Tasodifiy ravishda aralashtirish
            ->take(200) // 200 ta savolni tanlash
            ->values();

        // Tanlangan savollarni olish
        $questions = Question::whereIn('id', $randomIds)->get();

        // Har bir savol uchun javoblarni aralashtirish
        foreach ($questions as $question) {
            $options = $question->options()->get()->shuffle();
            $question->randomOptions = $options;
            $question->setRelation('options', $options);
        }

        // Testni boshlash vaqti
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
        Log::info('Test duration saved:', [
            'minutes' => $minutes,
            'seconds' => $seconds,
            'test_id' => $testAttempt->id
        ]);

        return redirect()->route('test.result', $testAttempt);
    }

    public function result(TestAttempt $testAttempt)
    {
        $testAttempt->load(['answers.question.options', 'answers.selectedOption']);

        // Agar answers bo‘sh bo‘lsa
        if ($testAttempt->answers->isEmpty()) {
            return redirect()->route('test.history')->with('error', 'Bu testga oid javoblar topilmadi.');
        }

        // Endi firstWhere() metodini xavfsiz ishlatish mumkin
        $selectedAnswer = $testAttempt->answers->firstWhere('question_id', 1); // Misol

        return view('test.result', compact('testAttempt'));
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

    public function deleteAllResults()
    {
        // Delete all test answers first (due to foreign key constraint)
        TestAnswer::truncate();

        // Then delete all test attempts
        TestAttempt::truncate();

        return redirect()->route('test.history')
            ->with('success', 'Барча натижалар ўчирилди');
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
