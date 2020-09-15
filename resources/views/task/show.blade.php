@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        Show
                        <a class="btn btn-danger float-right" href="{{ route('task.index') }}">Back</a>
                    </div>

                    <div class="card-body">
                        Title: {{ $task->title }} <br>
                        Created Date: {{ $task->created_at }} <br>
                        Updated Date: {{ $task->updated_at }} <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
