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
                        @csrf
                        <div class="input-group input-group-md">
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
                                    <td><a href="{{ route('task.index', $board->slug_id) }}" style="color: inherit  ">{{ $board->title }}</a></td>
                                    <td>{{ $board->created_at }}</td>
                                    <td>{{ $board->updated_at }}</td>
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
