@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>ЯНГИ САВОЛ КУШИШ</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('questions.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="question_text" class="form-label">Question Text</label>
                            <textarea name="question_text" id="question_text" class="form-control @error('question_text') is-invalid @enderror" rows="3" required>{{ old('question_text') }}</textarea>
                            @error('question_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Options</label>
                            @for($i = 0; $i < 4; $i++)
                                <div class="input-group mb-2">
                                    <div class="input-group-text">
                                        <input type="radio" name="correct_option" value="{{ $i }}" class="form-check-input mt-0" {{ old('correct_option') == $i ? 'checked' : '' }} required>
                                    </div>
                                    <input type="text" name="options[]" class="form-control @error('options.'.$i) is-invalid @enderror" placeholder="Option {{ $i + 1 }}" value="{{ old('options.'.$i) }}" required>
                                    @error('options.'.$i)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endfor
                            <div class="form-text">Select the radio button next to the correct answer.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('questions.index') }}" class="btn btn-secondary">Қайта қўйишиш</a>
                            <button type="submit" class="btn btn-primary">Сақлаш</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
