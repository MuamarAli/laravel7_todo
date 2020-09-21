<?php

namespace App\Http\Controllers;

use App\Board;
use App\Http\Requests\TaskListRequest;
use App\TaskList;
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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        $board = Board::where('slug', $slug)->get()[0];

        return view(
            'task.index',
            [
                'tasks' => $board->taskList,
                'board' => $board
            ]
        );
    }

    /**
     * @param $slug
     * @return false|string
     */
    public function testing($slug)
    {
        $board = Board::where('slug', $slug)->get()[0];
        $taskList['data'] = $board->taskList;

        return json_encode($taskList);
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
    public function store(TaskListRequest $taskListRequest, $slug)
    {
        $board = Board::where('slug', $slug)->get()[0];

        $task = new TaskList();

        $task->title = $taskListRequest->title;
        $task->slug = $this->checkSlug($taskListRequest->title);
        $task->user_id = Auth::user()->id;
        $task->board_id = $board->id;
        $task->save();

        return redirect()
            ->route('task.index', $board->slug)
            ->with('status-list', 'Successfully Inserted!');
    }

    /**
     * Display the specified resource.
     *
     * @param  TaskList $task
     * @return \Illuminate\Http\Response
     */
    public function show(TaskList $task)
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

    public function checkSlug(string $fullName, int $i = 0)
    {
        try {
            $slug = Str::slug($fullName);
            if ($i > 0) {
                if ($this->isSlugExist($slug . '-' . $i)) {
                    return $this->checkSlug($slug, $i + 1);
                } else {
                    return $slug . '-' . $i;
                }
            } else {
                if ($this->isSlugExist($slug)) {
                    return $this->checkSlug($slug, $i + 1);
                } else {
                    return $slug;
                }
            }
        } catch (\Exception $e) {
            throw new \Exception(
                'There\'s an error in creating the slug.'
            );
        }
    }

    public function isSlugExist(string $slug): bool
    {
        try {
            $entity = TaskList::where('slug', $slug)->get()[0];

            $result = empty($entity) ? false : true;
        } catch (\Exception $e) {
            $result = false;
        }

        return $result;
    }
}
