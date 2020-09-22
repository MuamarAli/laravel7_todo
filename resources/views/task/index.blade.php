@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mb-5">
        <div class="col-md">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">
                        <div class="input-group">
                            <input type="text" class="form-control update_board" id="{{ $board->slug_id }}" value="{{ $board->title }}">
                                <div class="input-group-append ml-3">
                                    <form action="{{ route('board.destroy', $board->slug_id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="close"><span aria-hidden="true">&times;</span></button>
                                    </form>
                                </div>
                        </div>
                </div>

                <div class="card-body">
                    @if (session('status-list'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status-list') }}
                        </div>
                    @endif

                    <form action="{{ route('task.store', $board->slug_id) }}" method="POST">
                        @csrf
                        <div class="input-group input-group-md">
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
                            </tr>
                        </thead>

                        <tbody>
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
                                <div class="input-group">
                                    <input type="text" class="form-control update_task" id="{{ $task->slug_id }}" value="{{ $task->title }}">
                                    <div class="input-group-append ml-3">
                                        <form action="{{ route('task.destroy', [$board->slug_id, $task->slug_id]) }}" method="POST">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="close"><span aria-hidden="true">&times;</span></button>
                                        </form>
                                    </div>
                                </div>
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
                                        <td scope="row" colspan="10" class="text-center">No Task Items found.</td>
                                    </tr>
                                @else
                                    @foreach ($task->taskItem as $item)
                                        <form
                                            id="form_is_done{{ $item->slug_id }}"
                                            action="{{ route('item.isDone', $item->slug_id) }}"
                                            method="POST"
                                        >
                                            @csrf
                                            <input type="hidden" name="list_item_id" value="{{ $item->slug_id }}">
                                            <input type="hidden" name="is_done" value="{{ $item->is_done }}">

                                            <tr>
                                                <td>{{ $item->task }}</td>
                                                <td>
                                                    <input
                                                        onchange="document.getElementById('form_is_done{{ $item->slug_id }}').submit()"
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
        $('.update_task').change(function() {
            let title = $(this).val();
            let board = {slugId : "{{ $board->slug_id }}"};
            let taskSlugId = $(this).attr('id');
            let url = '{{ route("task.update", [":slugId", ":slugId"]) }}';

            url = url.replace(':slugId', board.slugId );
            url = url.replace(':slugId', taskSlugId );

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "put",
                url: url,
                data: {
                  title: title
                },
                success: function(data) {
                    getAll();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });

        $('.update_board').change(function() {
            let title = $(this).val();
            let boardSlugId = $(this).attr('id');
            let url = '{{ route("board.update", ":slugId") }}';
            url = url.replace(':slugId', boardSlugId);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "put",
                url: url,
                data: {
                    title : title
                },
                success: function(data) {
                    console.log(data);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });

        getAll();

        function getAll() {
            let board = {slugId : "{{ $board->slug_id }}"};
            let url = '{{ route("task.fetchAll", ":slugId") }}';
            url = url.replace(':slugId', board.slugId);

            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                success: function(response){
                    let len = 0;

                    $('#listTable tbody').empty();
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
                            "<td align='center' colspan='4'>No Task List found.</td>" +
                            "</tr>";

                        $("#listTable tbody").append(tr_str);
                    }
                }
            });
        }
    });
</script>
@endsection
