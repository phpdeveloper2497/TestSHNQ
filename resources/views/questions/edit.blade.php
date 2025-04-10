@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Question</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('questions.update', $question) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="question_text" class="form-label">Question Text</label>
                            <textarea name="question_text" id="question_text" class="form-control @error('question_text') is-invalid @enderror" rows="3" required>{{ old('question_text', $question->question_text) }}</textarea>
                            @error('question_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Options</label>
                            @foreach($question->options as $index => $option)
                                <div class="input-group mb-2">
                                    <div class="input-group-text">
                                        <input type="radio" name="correct_option" value="{{ $index }}" class="form-check-input mt-0" {{ $option->is_correct ? 'checked' : '' }} required>
                                    </div>
                                    <input type="text" name="options[]" class="form-control @error('options.'.$index) is-invalid @enderror" placeholder="Option {{ $index + 1 }}" value="{{ old('options.'.$index, $option->option_text) }}" required>
                                    @error('options.'.$index)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                            <div class="form-text">Select the radio button next to the correct answer.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('questions.index') }}" class="btn btn-secondary">Back to Questions</a>
                            <button type="submit" class="btn btn-primary">Update Question</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
