<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'question' => 'What is the capital of Uzbekistan?',
                'options' => [
                    ['text' => 'Tashkent', 'correct' => true],
                    ['text' => 'Samarkand', 'correct' => false],
                    ['text' => 'Bukhara', 'correct' => false],
                    ['text' => 'Nukus', 'correct' => false],
                ],
            ],
            [
                'question' => 'What is 2 + 2?',
                'options' => [
                    ['text' => '3', 'correct' => false],
                    ['text' => '4', 'correct' => true],
                    ['text' => '5', 'correct' => false],
                    ['text' => '6', 'correct' => false],
                ],
            ],
            // Add more questions here
        ];

        foreach ($questions as $q) {
            $question = \App\Models\Question::create([
                'question_text' => $q['question']
            ]);

            foreach ($q['options'] as $opt) {
                \App\Models\Option::create([
                    'question_id' => $question->id,
                    'option_text' => $opt['text'],
                    'is_correct' => $opt['correct']
                ]);
            }
        }
    }
}
