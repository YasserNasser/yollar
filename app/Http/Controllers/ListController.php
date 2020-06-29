<?php

namespace App\Http\Controllers;
use App\Models\Board;
use App\Models\Lists;
use App\Models\Card;
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

    public function show(Board $board, Lists $list)
    {
        if ($list->board->is($board))
        if(Auth::user()->id !== $board->user_id){
            return response()->json(['status' => 'error','message'=>'Unauthorized'], 401);
        }

        return response()->json(['status' => 'success', 'list' => $list]);
    }

    public function store(Request $request, $boardId)
    {
        $board = Board::find($boardId);
        if(Auth::user()->id !== $board->user_id){
            return response()->json(['status' => 'error','message'=>'Unauthorized'], 401);
        }

        $newList =$board->lists()->create([
            'name' => $request->name,
            'board_id' =>$boardId
        ]);
        return response()->json(['message' => 'success','list'=>$newList], 200);
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
        //$list->cards->delete();
//         foreach ($cards as $card){
//             $card->destroy($card.id);
//             $card->save();
//        }
        if($list->delete($listId)) {
            return response()->json(['status' => 'success','message'=>'List Deleted successfully'], 200);
        }

        return response()->json(['status' => 'error','message'=>'Somthing went WRONG'], 404);
    }
}
