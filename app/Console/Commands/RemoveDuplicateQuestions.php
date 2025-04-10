<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveDuplicateQuestions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-duplicate-questions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove duplicate questions from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Finding duplicate questions...');

        // Find duplicate questions
        $duplicates = DB::table('questions')
            ->select('question_text')
            ->groupBy('question_text')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($duplicates->isEmpty()) {
            $this->info('No duplicate questions found.');
            return;
        }

        $this->info(sprintf('Found %d duplicate questions.', $duplicates->count()));

        foreach ($duplicates as $duplicate) {
            // Keep the first occurrence and delete others
            $questions = DB::table('questions')
                ->where('question_text', $duplicate->question_text)
                ->orderBy('id')
                ->get();

            $this->info(sprintf('Processing question: %s', $duplicate->question_text));

            // Skip the first one (keep it)
            $questions->shift();

            // Delete the rest
            foreach ($questions as $question) {
                // Delete related options first
                DB::table('options')
                    ->where('question_id', $question->id)
                    ->delete();

                // Delete the question
                DB::table('questions')
                    ->where('id', $question->id)
                    ->delete();

                $this->info(sprintf('Deleted duplicate question ID: %d', $question->id));
            }
        }

        $this->info('Successfully removed all duplicate questions.');
    }
}
