<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with('options')->orderBy('id', 'desc')->get();
        return view('questions.index', compact('questions'));
    }

    public function create()
    {
        return view('questions.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'question_text' => 'required|min:3',
                'options' => 'required|array|size:4',
                'options.*' => 'required|string|min:1',
                'correct_option' => 'required|integer|min:0|max:3'
            ]);

            $question = Question::create([
                'question_text' => $request->question_text
            ]);

            foreach ($request->options as $index => $optionText) {
                if (!empty($optionText)) {
                    $question->options()->create([
                        'option_text' => $optionText,
                        'is_correct' => $index === (int)$request->correct_option
                    ]);
                }
            }

            return redirect()->route('questions.index')
                ->with('success', 'Question created successfully');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) { // MySQL duplicate entry error code
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['This question already exists']);
            }
            throw $e;
        }
    }

    public function edit(Question $question)
    {
        $question->load('options');
        return view('questions.edit', compact('question'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => 'required|min:3',
            'options' => 'required|array|size:4',
            'options.*' => 'required|string|min:1',
            'correct_option' => 'required|integer|min:0|max:3'
        ]);

        $question->update([
            'question_text' => $request->question_text
        ]);

        // Delete existing options
        $question->options()->delete();

        // Create new options
        foreach ($request->options as $index => $optionText) {
            $question->options()->create([
                'option_text' => $optionText,
                'is_correct' => $index === (int)$request->correct_option
            ]);
        }

        return redirect()->route('questions.index')->with('success', 'Question updated successfully');
    }

    public function destroy(Question $question)
    {
        $question->delete(); // This will also delete related options due to cascade
        return redirect()->route('questions.index')->with('success', 'Question deleted successfully');
    }
}
