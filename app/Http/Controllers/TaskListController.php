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
    public function index($slugId)
    {
        $board = Board::where('slug_id', $slugId)->get()[0];

        return view(
            'task.index',
            [
                'tasks' => $board->taskList,
                'board' => $board
            ]
        );
    }

    /**
     * Get all using ajax
     *
     * @param $slug
     * @return false|string
     */
    public function testing($slugId)
    {
        $board = Board::where('slug_id', $slugId)->get()[0];
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
    public function store(TaskListRequest $taskListRequest, $slugId)
    {
        $board = Board::where('slug_id', $slugId)->get()[0];

        $task = new TaskList();

        $task->title = $taskListRequest->title;
        $task->slug = $this->checkSlug($taskListRequest->title);
        $task->slug_id = $this->generateSlugId();
        $task->user_id = Auth::user()->id;
        $task->board_id = $board->id;
        $task->save();

        return redirect()
            ->route('task.index', $board->slug_id)
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
    public function update(Request $request, $slug, $slugId)
    {
        if ($request->ajax()) {
            TaskList::where('slug_id', $slugId)->update([
                'title' => $request->title,
                'slug' => $this->checkSlug($request->title)
            ]);

            return response()->json('updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($boardSlugId, $listSlug)
    {
        $board = Board::where('slug_id', $boardSlugId)->get()[0];

        TaskList::where('slug_id', $listSlug)->delete();

        return redirect()
            ->route('task.index', $board->slug_id)
            ->with('status-list', 'Successfully Deleted!');
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

    /**
     * @throws \Exception
     *
     * @return string
     */
    public function generateSlugId()
    {
        try {
            $boardId = TaskList::count();

            if ($boardId <= 0) {
                $boardId++;

                $result = $this->slugifyId($boardId, 'b');
            } else {
                $trim = ltrim(
                    TaskList::latest()->orderBy('created_at', 'DESC')->first()->slug_id,
                    'b'
                );

                $deSlugId = intval(ltrim($trim, '0'));
                $deSlugId++;

                $result = $this->slugifyId($deSlugId, 'b');
            }
        } catch (\Exception $e) {
            throw new \Exception(
                'An error occurred at generating the slug id'
            );
        }

        return $result;
    }

    /**
     * @param int $id
     * @param string $prefix
     *
     * @return string
     */
    public function slugifyId(int $id, string $prefix = ''): string
    {
        try {
            //Adds trailing zeroes and a prefix
            $slug = (string)sprintf('%04s', $id);

            if (!empty($prefix)) {
                $slug = (string)$prefix . $slug;
            }
        } catch (\Exception $e) {
            throw new $e;
        }

        return $slug;
    }
}
