<?php

namespace App\Http\Controllers;

use App\Board;
use App\Http\Requests\TaskItemRequest;
use App\TaskItem;
use App\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class TaskItemController
 *
 * @package App\Http\Controllers
 */
class TaskItemController extends Controller
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
    public function index()
    {
        //
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
     * @param  TaskItemRequest $taskItemRequest
     * @param  TaskList $taskList
     * @return \Illuminate\Http\Response
     */
    public function store(TaskItemRequest $taskItemRequest, $slug)
    {
        $taskList = TaskList::where('slug', $slug)->get()[0];
        $board = Board::findOrFail($taskList->board_id);

        $item = new TaskItem();

        $item->task = $taskItemRequest->task;
        $item->slug = $this->checkSlug($taskItemRequest->task);
        $item->user_id = Auth::user()->id;
        $item->list_id = $taskList->id;
        $item->save();

        return redirect()
            ->route('task.index', $board->slug)
            ->with('status', 'Successfully Inserted!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TaskItem  $taskItem
     * @return \Illuminate\Http\Response
     */
    public function show(TaskItem $taskItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TaskItem  $taskItem
     * @return \Illuminate\Http\Response
     */
    public function edit(TaskItem $taskItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaskItem  $taskItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $listSlug, $value)
    {
        if ($request->ajax()) {
            TaskList::where('slug', $listSlug)->update(['title' => $value]);

            return response()->json('updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaskItem  $taskItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskItem $taskItem)
    {
        //
    }

    /**
     * Set task as done.
     *
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function isDone(Request $request, $slug)
    {
        $item = Auth::user()->listItems()->where('slug', $slug)->get()[0];
        $item->is_done = !$request->is_done;
        $item->save();

        return redirect()->back();
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
            $entity = TaskItem::where('slug', $slug)->get()[0];

            $result = empty($entity) ? false : true;
        } catch (\Exception $e) {
            $result = false;
        }

        return $result;
    }
}
