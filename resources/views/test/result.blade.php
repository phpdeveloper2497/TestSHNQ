@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>ТЕСТ НАТИЖАЛАРИ</h3>
                    <div class="text-muted">
                        Натижалар: {{ $testAttempt->score }}/{{ $testAttempt->answers->count() }}
                    </div>
                    <div class="text-muted">
                        <p>Тест якунланган вақт: {{ \Carbon\Carbon::parse($testAttempt->finished_at)->format('d.m.Y H:i') }}</p>
                    </div>
                    <div class="text-muted">
                        Сарфланган вақт: @if(isset($minutes) && isset($seconds))
                            {{ $minutes }} дақиқа {{ $seconds }} сония
                        @else
                            - дақиқа - сония
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    @foreach($testAttempt->answers as $index => $answer)
                        <div class="question-container mb-4 {{ $answer->selectedOption->is_correct ? 'border-success' : 'border-danger' }} border p-3">
                            <h5>{{ $index + 1 }}. {{ $answer->question->question_text }}</h5>
                            @foreach($answer->question->options as $option)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" 
                                        disabled
                                        {{ $option->id === $answer->selectedOption->id ? 'checked' : '' }}>
                                    <label class="form-check-label {{ $option->is_correct ? 'text-success font-weight-bold' : '' }} 
                                        {{ $option->id === $answer->selectedOption->id && !$option->is_correct ? 'text-danger' : '' }}">
                                        {{ $option->option_text }}
                                        @if($option->is_correct)
                                            <i class="fas fa-check text-success"></i>
                                        @endif
                                        @if($option->id === $answer->selectedOption->id && !$option->is_correct)
                                            <i class="fas fa-times text-danger"></i>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    <div class="text-center">
                        <a href="{{ route('test.start') }}" class="btn btn-primary">ЯНГИ ТЕСТ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
