<?php

namespace App\Http\Controllers;
use App\Models\Board;
use App\Models\Lists;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
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
    public function index($boardId)
    {
        $board = Board::findOrFail($boardId);
        if(Auth::user()->id !== $board->user_id){
            return response()->json(['status' => 'error','message'=>'Unauthorized'], 401);
        }
        return response()->json(['lists' => $board->lists]);
    }

    public function show($boardId,$listId)
    {
        $board = Board::findOrFail($boardId);
        if(Auth::user()->id !== $board->user_id){
            return response()->json(['status' => 'error','message'=>'Unauthorized'], 401);
        }
        $list = $board->lists()->find($listId);

        return response()->json(['status' => 'success', 'list' => $list]);
    }

    public function store(Request $request, $boardId)
    {
        $board = Board::findOrFail($boardId);

        $board()->lists()->create([
            'name' => $request->name,
        ]);
        return response()->json(['message' => 'success'], 200);
    }
    public function update(Request $request, $boardId, $listId)
    {
        $board = Board::find($boardId);
        if(Auth::user()->id !== $board->user_id){
            return response()->json(['status' => 'error','message'=>'Unauthorized'], 401);
        }
        $list = Lists::findOrFail($listId);
        $list->update($request->all());
        $list->save();
                //return $board->lists()->find($listId);
        return response()->json(['message' => 'success','list'=>$list], 200);
    }
    public function destroy($boardId, $listId)
    {
        $board = Board::find($boardId);
        if(Auth::user()->id !== $board->user_id){
            return response()->json(['status' => 'error','message'=>'Unauthorized'], 401);
        }
        $list = Lists::findOrFail($listId);
        if($list->destroy($listId)) {
            return response()->json(['status' => 'success','message'=>'List Deleted successfully'], 200);
        }
        return response()->json(['status' => 'error','message'=>'Somthing went WRONG'], 404);
    }
}
