@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>САВОЛЛАР</h3> 
                    <h4>Базадаги жами саволлар сони: {{ $questions->count() }}</h4>
                    <a href="{{ route('questions.create') }}" class="btn btn-primary">ЯНГИ САВОЛ КУШИШ</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>САВОЛЛАР</th>
                                    <th>Жавоблар</th>
                                    <th>Ҳаракатлар</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($questions as $question)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $question->question_text }}</td>
                                        <td>
                                            <ul class="list-unstyled">
                                                @foreach($question->options as $option)
                                                    <li class="{{ $option->is_correct ? 'text-success fw-bold' : '' }}">
                                                        {{ $option->option_text }}
                                                        @if($option->is_correct)
                                                            <i class="fas fa-check text-success"></i>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('questions.edit', $question) }}" class="btn btn-sm btn-info">Edit</a>
                                                <form action="{{ route('questions.destroy', $question) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this question?')">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('test.start') }}" class="btn btn-success">ТЕСТНИ БОШЛАШ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
