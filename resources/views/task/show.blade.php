@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if (session('status-item'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status-item') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        {{ $task->title }}
                        <a class="btn btn-danger float-right" href="{{ route('task.index') }}">Back</a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('item.store', $task->id) }}" method="POST" class="form-inline">
                            @csrf
                            <div class="form-group">
                                <label for="task" class="sr-only">Password</label>
                                <input type="text" class="form-control" name="task" id="task" placeholder="Item name">
                                @error('task')
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary ml-3">Add Item</button>
                        </form>

                        @error('task')
                            <p style="color: red" class="mt-3">{{ $errors->first('task') }}</p>
                        @enderror

                        <table class="table mt-5">
                            <thead>
                            <tr>
                                <th scope="col">Task</th>
                                <th scope="col">IsDone</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if (count($task->taskItem) === 0)
                                <tr>
                                    <td scope="row" colspan="10" class="text-center">No Task found.</td>
                                </tr>
                            @else
                                @foreach ($task->taskItem as $item)
                                    <form
                                        id="formIsDone_{{ $item->id }}"
                                        action="{{ route('item.isDone', $item->id) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        <input type="hidden" name="list_item_id" value="{{ $item->id }}">
                                        <input type="hidden" name="is_done" value="{{ $item->is_done }}">

                                        <tr>
                                            <td>{{ $item->task }}</td>
                                            <td>
                                                <input
                                                    onchange="document.getElementById('formIsDone_{{ $item->id }}').submit()"
                                                    type="checkbox" {{ $item->is_done ? 'checked' : '' }}
                                                >
                                            </td>
                                        </tr>
                                    </form>
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
