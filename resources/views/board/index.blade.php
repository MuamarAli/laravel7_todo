@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md*">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('board.store') }}" method="POST">
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control" name="title" id="title" placeholder="Board name">

                            <div class="input-group-prepend">
                                <button type="submit" class="btn btn-outline-dark">Create List</button>
                            </div>
                        </div>

                        @error('title')
                        <p style="color: red" class="mt-3">{{ $errors->first('title') }}</p>
                        @enderror
                    </form>

                    <table class="table mt-5">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Created Date</th>
                            <th scope="col">Updated Date</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if (count($boards) === 0)
                            <tr>
                                <td scope="row" colspan="10" class="text-center">No Boards found.</td>
                            </tr>
                        @else
                            @foreach ($boards as $board)
                                <tr>
                                    <th scope="row">{{ $board->id }}</th>
                                    <td>{{ $board->title }}</td>
                                    <td>{{ $board->created_at }}</td>
                                    <td>{{ $board->updated_at }}</td>
                                    <td>
                                        <a href="{{ route('task.index', $board->slug) }}">Show</a>
                                        |
                                        <a href="#">Edit</a>
                                        |
                                        <a href="#">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
