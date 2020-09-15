<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskListRequest;
use App\TaskLists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class TaskListController
 *
 * @package App\Http\Controllers
 */
class TaskListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('task.index', ['tasks' => TaskLists::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TaskListRequest $taskListRequest
     * @return \Illuminate\Http\Response
     */
    public function store(TaskListRequest $taskListRequest)
    {
        $task = new TaskLists();

        $task->title = $taskListRequest->title;
        $task->slug = Str::slug($taskListRequest->slug);
        $task->user_id = Auth::user()->id;
        $task->save();

        return redirect()
            ->route('task.index')
            ->with('status', 'Successfully Inserted!');
    }

    /**
     * Display the specified resource.
     *
     * @param  TaskLists $task
     * @return \Illuminate\Http\Response
     */
    public function show(TaskLists $task)
    {
        return view('task.show', ['task' => $task]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
