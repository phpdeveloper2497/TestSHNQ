@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>БАРЧА НАТИЖАЛАР</h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>САНА</th>
                                    <th>НАТИЖА</th>
                                    <th>ВАҚТ</th>
                                    <th>ҲАРАКАТЛАР</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attempts as $index => $attempt)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $attempt['date'] }}</td>
                                        <td>
                                            {{ $attempt['score'] }}/{{ $attempt['total_questions'] }}
                                            @if($attempt['total_questions'] > 0)
                                                ({{ number_format($attempt['score'] / $attempt['total_questions'] * 100, 1) }}%)
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($attempt['minutes']) && isset($attempt['seconds']))
                                                {{ $attempt['minutes'] }} дақиқа {{ $attempt['seconds'] }} сония
                                            @else
                                                - дақиқа - сония
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('test.result', $attempt['id']) }}" class="btn btn-sm btn-info">
                                                БАТАФСИЛ
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('test.start') }}" class="btn btn-primary">ЯНГИ ТЕСТ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
