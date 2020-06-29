<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Card;
use App\Models\Lists;
use App\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
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
    public function index($boardId, $listId)
    {
        //$list = Lists::findOrFail($listId);

        $board = Board::findOrFail($boardId);
        if (Auth::user()->id !== $board->user_id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
        $list = $board->lists()->findOrFail($listId);
        return response()->json(['cards' => $list->cards]);
    }

    public function show($cardId)
    {
        $card = Card::findOrFail($cardId);
        if (Auth::user()->id !== $card->list->board->user_id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
        $user = $card->user;
        $card['user_name'] = $user->username;
        $card['comments'] = $card->comments;

        return response()->json(['status' => 'success', 'card' => $card]);
    }

    public function store(Request $request, $listId)
    {
        //$board = Board::findOrFail($boardId);

        $list = Lists::find($listId);
        $newCard = $list->cards()->create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => Auth::user()->id
        ]);
        $user = $newCard->user;
        $newCard['user_name'] = $user->username;
        return response()->json(['message' => 'success', 'card' => $newCard], 200);
    }
    public function update(Request $request,  $cardId)
    {
        $card = Card::findOrFail($cardId);
        if (Auth::user()->id !== $card->list->board->user_id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $card->update($request->all());
        $card->user_id = Auth::user()->id;
        $card->save();
        //return $board->lists()->find($listId);
        $user = $card->user;
        $card['user_name'] = $user->username;
        return response()->json(['message' => 'success', 'card' => $card], 200);
    }
    public function updateList(Request $request,  $cardId, $listId)
    {
        $card = Card::findOrFail($cardId);
        if (Auth::user()->id !== $card->list->board->user_id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $card->update(['lists_id' => $listId]);
        $card->user_id = Auth::user()->id;
        $card->save();
        //return $board->lists()->find($listId);
        $user = $card->user;
        $card['user_name'] = $user->username;
        return response()->json(['message' => 'success', 'card' => $card], 200);
    }
    public function updateAll(Request $request)
    {
        $newCards = $request->cards;
        //        if(Auth::user()->id !== $card->list->board->user_id){
        //            return response()->json(['status' => 'error','message'=>'Unauthorized'], 401);
        //        }
        $cards = Card::all();
        foreach ($cards as $card) {
            foreach ($newCards as $newCard) {
                if ($newCard['id'] == $card->id) {
                    $card->priority = $newCard['priority'];
                    $card->save();
                }
            }
        }
        
        return response()->json(['message' => 'success', 'cards' => $cards], 200);
    }
    public function destroy($cardId)
    {
        $card = Card::findOrFail($cardId);
        if (Auth::user()->id !== $card->list->board->user_id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }
        
        if ($card->delete($cardId)) {
            return response()->json(['status' => 'success', 'message' => 'Card Deleted successfully', 'card' => $card], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Somthing went WRONG'], 404);
    }
}
