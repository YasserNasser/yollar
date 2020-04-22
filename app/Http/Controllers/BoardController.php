<?php

namespace App\Http\Controllers;
use App\Models\Board;
use Illuminate\Http\Request;

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
    public function index()
    {
       return Board::all();
    }

    public function show($boardId)
        {
            $board = Board::findOrFail($boardId);
           return $board;
        }

    public function store(Request $request)
    {
        Board::create([
            'name' => $request->name,
            'user_id' => 1,
        ]);
        return response()->json(['message' => 'success'], 200);
    }
    public function update(Request $request, $boardId)
    {
        $board = Board::find($boardId);
        $board->update($request->all());
        //$board->save();

        return response()->json(['message' => 'success','board'=>$board], 200);
    }
    public function destroy($boardId)
    {
        if(Board::destroy($boardId)) {
            return response()->json(['status' => 'success','message'=>'Board Deleted successfully'], 200);
        }
        return response()->json(['status' => 'error','message'=>'Somthing went WRONG'], 404);
    }
}
