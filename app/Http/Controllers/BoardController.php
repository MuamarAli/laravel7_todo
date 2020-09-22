<?php

namespace App\Http\Controllers;

use App\Board;
use App\Http\Requests\BoardRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BoardController extends Controller
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
        return view('board.index', ['boards' => Auth::user()->boards]);
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
     * @param BoardRequest $boardRequest
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BoardRequest $boardRequest)
    {
        $board = new Board();

        $board->title = $boardRequest->title;
        $board->slug = $this->checkSlug($boardRequest->title);
        $board->slug_id = $this->generateSlugId();
        $board->user_id = Auth::user()->id;
        $board->save();

        return redirect()
            ->route('board.index')
            ->with('status', 'Successfully Inserted!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function show(Board $board)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Board  $board
     * @return \Illuminate\Http\Response
     */
    public function edit(Board $board)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $slugId
     * @throws \Exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $slugId)
    {
        if ($request->ajax()) {
            Board::where('slug_id', $slugId)->update([
                'title' => $request->title,
                'slug' => $this->checkSlug($request->title)
            ]);

            return response()->json('updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function destroy($slugId)
    {
        Board::where('slug_id', $slugId)->delete();

        return redirect()
            ->route('board.index')
            ->with('status', 'Successfully Deleted!');
    }

    /**
     * @param string $fullName
     * @param int $i
     * @throws \Exception
     *
     * @return string
     */
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

    /**
     * @param string $slug
     *
     * @return bool
     */
    public function isSlugExist(string $slug): bool
    {
        try {
            $entity = Board::where('slug', $slug)->get()[0];

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
            $boardId = Board::count();

            if ($boardId <= 0) {
                $boardId++;

                $result = $this->slugifyId($boardId, 'b');
            } else {
                $trim = ltrim(
                    Board::latest()->orderBy('created_at', 'DESC')->first()->slug_id,
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
