<?php

namespace App\Http\Controllers;
use App\Models\Board;
use App\Models\Card;
use App\Models\Lists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $boardsData = Auth::user()->boards->load(['lists.cards'=> function($query){
            $query->orderBy('priority','asc');
        }]);
        //$boardsData['comments'] = $boardsData->lists->cards->comments;
       return response()->json(['status' => 'success','boards'=>$boardsData], 200);
    }

    public function show($boardId)
        {
            $board = Board::findOrFail($boardId);
            if(Auth::user()->id !== $board->user_id){
                return response()->json(['status' => 'error','message'=>'Unauthorized'], 401);
            }
                 return $board;
        }

    public function store(Request $request)
    {
       $board = Auth::user()->boards()->create([
            'name' => $request->name,
        ]);
        return response()->json(['message' => 'success' ,'board'=>$board], 200);
    }
    public function update(Request $request, $boardId)
    {
        $board = Board::find($boardId);
        if(Auth::user()->id !== $board->user_id){
            return response()->json(['status' => 'error','message'=>'Unauthorized'], 401);
        }
        $board->update($request->all());
        //$board->save();

        return response()->json(['message' => 'success','board'=>$board], 200);
    }
    public function destroy($boardId)
    {
        $board = Board::find($boardId);
        if(Auth::user()->id !== $board->user_id){
            return response()->json(['status' => 'error','message'=>'Unauthorized'], 401);
        }
        //$lists = $board->lists();
        // we get cards with hasManyThrough relation ship between board ->lists->cards
//        $cards = $board->cards();
//        $cards->delete();
//        $lists->delete();
        if($board->delete()) {
            return response()->json(['status' => 'success','message'=>'Board Deleted successfully'], 200);
        }
        return response()->json(['status' => 'error','message'=>'Somthing went WRONG'], 404);
    }
}
