<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Find duplicate questions
        $duplicates = DB::table('questions')
            ->select('question_text')
            ->groupBy('question_text')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            // Keep the first occurrence and delete others
            $questions = DB::table('questions')
                ->where('question_text', $duplicate->question_text)
                ->orderBy('id')
                ->get();

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
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
