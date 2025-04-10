@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Test</h3>
                    <div id="timer" class="h4">25:00</div>
                </div>

                <div class="card-body">
                    <form id="testForm" action="{{ route('test.submit', $testAttempt) }}" method="POST">
                        @csrf
                        @foreach($questions as $index => $question)
                            <div class="question-container mb-4">
                                <h5>{{ $index + 1 }}. {{ $question->question_text }}</h5>
                                @foreach($question->options as $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" 
                                            name="answers[{{ $question->id }}]" 
                                            value="{{ $option->id }}" 
                                            id="q{{ $question->id }}_{{ $option->id }}"
                                            required>
                                        <label class="form-check-label" for="q{{ $question->id }}_{{ $option->id }}">
                                            {{ $option->option_text }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Жавобларни юбориш</button>
                            <!-- <button type="button" class="btn btn-secondary" onclick="submitForm()">Вактдан олдин якунлаш</button> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let timeLeft = 25 * 60; // 25 minutes in seconds
    const timerElement = document.getElementById('timer');
    
    const timer = setInterval(() => {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeLeft === 0) {
            clearInterval(timer);
            document.getElementById('testForm').submit();
        }
        timeLeft--;
    }, 1000);

    function submitForm() {
        if (confirm('Are you sure you want to finish the test early?')) {
            document.getElementById('testForm').submit();
        }
    }
</script>
@endpush
