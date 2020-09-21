@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mb-5">
        <div class="col-md">
            <div class="card">
                <div class="card-header">{{ $board->title }}</div>

                <div class="card-body">
                    @if (session('status-list'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status-list') }}
                        </div>
                    @endif

                    <form action="{{ route('task.store', $board->slug) }}" method="POST">
                        @csrf
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control" name="title" id="title" placeholder="Create todo">
                            <div class="input-group-prepend">
                                <button type="submit" class="btn btn-outline-dark">Create List</button>
                            </div>
                        </div>

                        @error('title')
                            <p style="color: red" class="mt-3">{{ $errors->first('title') }}</p>
                        @enderror
                    </form>

                    <table id="listTable" class="table mt-5">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Created Date</th>
                                <th scope="col">Updated Date</th>
                                {{--<th scope="col">Action</th>--}}
                            </tr>
                        </thead>

                        <tbody data-board="{{ $board->slug }}">
                        {{--@if (count($tasks) === 0)--}}
                            {{--<tr>--}}
                                {{--<td scope="row" colspan="10" class="text-center">No Tasks found.</td>--}}
                            {{--</tr>--}}
                        {{--@else--}}
                            {{--@foreach ($tasks as $task)--}}
                                {{--<tr>--}}
                                    {{--<th scope="row">{{ $task->id }}</th>--}}
                                    {{--<td>{{ $task->title }}</td>--}}
                                    {{--<td>{{ $task->created_at }}</td>--}}
                                    {{--<td>{{ $task->updated_at }}</td>--}}
                                    {{--<td>--}}
                                        {{--<a href="{{ route('task.show', $task) }}">Show</a>--}}
                                        {{--|--}}
                                        {{--<a href="#">Edit</a>--}}
                                        {{--|--}}
                                        {{--<a href="#">Delete</a>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                            {{--@endforeach--}}
                        {{--@endif--}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @if (count($tasks) === 0)

        @else
            @foreach ($tasks as $task)
                <div class="col-md mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">
                                <input type="text" class="form-control" name="updateTask" data-task="{{ $task->slug }}" value="{{ $task->title }}">
                            </h6>
                        </div>

                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Task</th>
                                        <th scope="col">Complete</th>
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
                                            action="{{ route('item.isDone', $item->slug) }}"
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
                        <div class="card-footer">
                            <form action="{{ route('item.store', $task->slug) }}" method="POST">
                                @csrf
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" name="task" id="task" placeholder="Add Item">
                                    <div class="input-group-prepend">
                                        <button type="submit" class="btn btn-outline-dark">Add Item</button>
                                    </div>
                                </div>

                                @error('task')
                                    <p style="color: red" class="mt-3">{{ $errors->first('task') }}</p>
                                @enderror
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready( function () {
        $('input[name=updateTask]').change(function() {
            let value = $(this).val();
            let listSlug = $(this).data().task;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "put",
                url: listSlug + "/items/" + value,
                success: function(data) {
                    getAll();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });

        getAll();

        function getAll() {
            let slug = $('tbody').data().board;

            $.ajax({
                url: slug + '/testing',
                type: 'get',
                dataType: 'json',
                success: function(response){
                    let len = 0;

                    $('#listTable tbody').empty(); // Empty <tbody>
                    if (response['data'] != null){
                        len = response['data'].length;
                    }

                    if(len > 0){
                        for(let i = 0; i < len; i++){
                            // let id = response['data'][i].id;
                            let title = response['data'][i].title;
                            let createdAt = response['data'][i].created_at;
                            let updatedAt = response['data'][i].updated_at;

                            let tr_str = "<tr>" +
                                "<td>" + (i+1) + "</td>" +
                                "<td>" + title + "</td>" +
                                "<td>" + createdAt + "</td>" +
                                "<td>" + updatedAt + "</td>" +
                                "</tr>";

                            $("#listTable tbody").append(tr_str);
                        }
                    } else {
                        let tr_str = "<tr>" +
                            "<td align='center' colspan='4'>No record found.</td>" +
                            "</tr>";

                        $("#listTable tbody").append(tr_str);
                    }
                }
            });
        }
    });
</script>
@endsection
