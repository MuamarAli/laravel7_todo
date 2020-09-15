@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Task Lists') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('task.store') }}" method="POST" class="form-inline">
                        @csrf
                        <div class="form-group">
                            <label for="title" class="sr-only">Password</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="List name">
                            @error('title')
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary ml-3">Create List</button>
                    </form>

                    <p style="color: red" class="mt-3">{{ $errors->first('title') }}</p>

                    <table class="table mt-5">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Created Date</th>
                            <th scope="col">Updated Date</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if (count($tasks) === 0)
                            <tr>
                                <td scope="row" colspan="10" class="text-center">No Tasks found.</td>
                            </tr>
                        @else
                            @foreach ($tasks as $task)
                                <tr>
                                    <th scope="row">{{ $task->id }}</th>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ $task->created_at }}</td>
                                    <td>{{ $task->updated_at }}</td>
                                    <td>
                                        <a href="{{ route('task.show', $task) }}">Show</a>
                                        |
                                        <a href="#">Edit</a>
                                        |
                                        <a href="#">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
